import tinymce from 'tinymce/tinymce';

import 'tinymce/icons/default';
import 'tinymce/themes/silver';
import 'tinymce/models/dom';

import 'tinymce/plugins/lists';
import 'tinymce/plugins/link';
import 'tinymce/plugins/image';
import 'tinymce/plugins/table';
import 'tinymce/plugins/code';
import 'tinymce/plugins/preview';
import 'tinymce/plugins/fullscreen';
import 'tinymce/plugins/wordcount';

import 'tinymce/skins/ui/oxide/skin.min.css';
import 'tinymce/skins/ui/oxide/content.min.css';
import 'tinymce/skins/content/default/content.min.css';

window.tinymce = tinymce;

window.initAdminRichTextEditors = function () {
    document
        .querySelectorAll('textarea.js-admin-rich-text:not([data-tinymce-ready])')
        .forEach((textarea) => {
            textarea.setAttribute('data-tinymce-ready', 'true');

            tinymce.init({
                target: textarea,
                license_key: 'gpl',

                skin: false,
                content_css: false,

                height: Number(textarea.dataset.height || 520),
                menubar: false,
                branding: false,
                promotion: false,

                plugins: [
                    'lists',
                    'link',
                    'image',
                    'table',
                    'code',
                    'preview',
                    'fullscreen',
                    'wordcount',
                ],

                toolbar: [
                    'undo redo',
                    'blocks',
                    'bold italic underline',
                    'alignleft aligncenter alignright alignjustify',
                    'bullist numlist outdent indent',
                    'blockquote link image imageSize table',
                    'removeformat preview fullscreen code',
                ].join(' | '),

                block_formats:
                    'Paragraph=p; Heading 2=h2; Heading 3=h3; Heading 4=h4; Quote=blockquote',

                image_caption: false,
                image_title: true,
                image_dimensions: true,
                image_advtab: true,
                object_resizing: 'img',
                resize_img_proportional: true,
                paste_data_images: false,

                paste_as_text: false,
                paste_webkit_styles: 'none',
                paste_remove_styles_if_webkit: true,
                paste_merge_formats: true,
                smart_paste: true,

                automatic_uploads: true,
                images_upload_credentials: true,
                images_reuse_filename: false,
                file_picker_types: 'image',

                images_upload_handler: (blobInfo) => {
                    return new Promise((resolve, reject) => {
                        const csrfToken = document
                            .querySelector('meta[name="csrf-token"]')
                            ?.getAttribute('content');

                        const uploadUrl = textarea.dataset.uploadUrl;

                        if (!uploadUrl) {
                            reject('Upload URL is not configured.');
                            return;
                        }

                        const formData = new FormData();
                        formData.append('file', blobInfo.blob(), blobInfo.filename());

                        fetch(uploadUrl, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': csrfToken || '',
                                Accept: 'application/json',
                            },
                            body: formData,
                        })
                            .then(async (response) => {
                                const data = await response.json();

                                if (!response.ok) {
                                    reject(
                                        data?.message ||
                                            data?.errors?.file?.[0] ||
                                            'Image upload failed.'
                                    );
                                    return;
                                }

                                if (!data.location) {
                                    reject('Invalid upload response.');
                                    return;
                                }

                                resolve(data.location);
                            })
                            .catch(() => {
                                reject('Image upload failed.');
                            });
                    });
                },

                table_default_attributes: {
                    border: '1',
                },

                table_default_styles: {
                    width: '100%',
                    borderCollapse: 'collapse',
                },

                content_style: `
                    body {
                        font-family: Inter, ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
                        font-size: 16px;
                        line-height: 1.75;
                        color: #0f172a;
                        padding: 16px;
                    }

                    h2 {
                        font-size: 1.875rem;
                        line-height: 2.25rem;
                        font-weight: 800;
                        margin: 1.5rem 0 0.75rem;
                    }

                    h3 {
                        font-size: 1.5rem;
                        line-height: 2rem;
                        font-weight: 800;
                        margin: 1.25rem 0 0.5rem;
                    }

                    h4 {
                        font-size: 1.25rem;
                        line-height: 1.75rem;
                        font-weight: 700;
                        margin: 1rem 0 0.5rem;
                    }

                    p {
                        margin: 0 0 1rem;
                    }

                    ul,
                    ol {
                        padding-left: 1.5rem;
                        margin: 0 0 1rem;
                    }

                    ul {
                        list-style-type: disc;
                    }

                    ol {
                        list-style-type: decimal;
                    }

                    li {
                        margin: 0.25rem 0;
                    }

                    blockquote {
                    border-left: 4px solid #cbd5e1;
                    margin: 1rem 0;
                    padding: 0.75rem 1rem;
                    background: #f8fafc;
                    color: #475569;
                    }

                    img {
                        max-width: 100%;
                        height: auto;
                        border-radius: 14px;
                        margin: 1rem 0;
                    }

                    img.rich-image-small {
                        width: 240px;
                    }

                    img.rich-image-medium {
                        width: 420px;
                    }

                    img.rich-image-large {
                        width: 680px;
                    }

                    img.rich-image-full {
                        width: 100%;
                    }
                    table {
                        border-collapse: collapse;
                        width: 100%;
                        margin: 1rem 0;
                    }

                    table td,
                    table th {
                        border: 1px solid #cbd5e1;
                        padding: 0.75rem;
                    }

                    a {
                        color: #2563eb;
                        text-decoration: underline;
                    }
                `,

setup(editor) {
    const imageSizeClasses = [
        'rich-image-small',
        'rich-image-medium',
        'rich-image-large',
        'rich-image-full',
    ];

    function getSelectedImage() {
        const node = editor.selection.getNode();

        if (node && node.nodeName === 'IMG') {
            return node;
        }

        return null;
    }

    function applyImageSize(sizeClass) {
        const image = getSelectedImage();

        if (!image) {
            editor.notificationManager.open({
                text: 'Please select an image first.',
                type: 'info',
                timeout: 2500,
            });

            return;
        }

        imageSizeClasses.forEach((className) => {
            image.classList.remove(className);
        });

        image.removeAttribute('width');
        image.removeAttribute('height');
        image.style.width = '';
        image.style.height = '';

        image.classList.add(sizeClass);

        editor.dom.setAttrib(image, 'class', image.className);
        editor.save();

        textarea.dispatchEvent(
            new Event('input', {
                bubbles: true,
            })
        );
    }

    editor.ui.registry.addMenuButton('imageSize', {
        text: 'Image Size',
        fetch(callback) {
            callback([
                {
                    type: 'menuitem',
                    text: 'Small',
                    onAction() {
                        applyImageSize('rich-image-small');
                    },
                },
                {
                    type: 'menuitem',
                    text: 'Medium',
                    onAction() {
                        applyImageSize('rich-image-medium');
                    },
                },
                {
                    type: 'menuitem',
                    text: 'Large',
                    onAction() {
                        applyImageSize('rich-image-large');
                    },
                },
                {
                    type: 'menuitem',
                    text: 'Full Width',
                    onAction() {
                        applyImageSize('rich-image-full');
                    },
                },
            ]);
        },
    });

            editor.on('change keyup undo redo input', () => {
                editor.save();

                textarea.dispatchEvent(
                    new Event('input', {
                        bubbles: true,
                    })
                );
            });

            editor.on('init', () => {
                editor.save();
            });
        },
            });
        });
};

document.addEventListener('DOMContentLoaded', () => {
    window.initAdminRichTextEditors();
});

document.addEventListener(
    'submit',
    () => {
        if (window.tinymce) {
            window.tinymce.triggerSave();
        }
    },
    true
);