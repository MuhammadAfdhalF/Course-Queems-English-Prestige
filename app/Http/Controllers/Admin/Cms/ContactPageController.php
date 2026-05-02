<?php

namespace App\Http\Controllers\Admin\Cms;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use App\Models\ContactSocialLink;
use Illuminate\Http\Request;

class ContactPageController extends Controller
{
    public function index()
    {
        $contact = Contact::query()
            ->with(['socialLinks' => function ($query) {
                $query->orderBy('sort_order')->latest();
            }])
            ->first();

        $socialLinks = $contact
            ? $contact->socialLinks
            : collect();

        $nextSortOrder = $contact
            ? ((int) $contact->socialLinks()->max('sort_order') + 1)
            : 1;

        return view('pages.admin.cms.contact.index', compact(
            'contact',
            'socialLinks',
            'nextSortOrder'
        ));
    }

    public function save(Request $request)
    {
        $validated = $request->validate([
            'whatsapp_label' => ['nullable', 'string', 'max:50'],
            'email_label' => ['nullable', 'email', 'max:255'],
            'operational_hours' => ['nullable', 'string', 'max:255'],
            'address' => ['nullable', 'string'],
            'map_embed_url' => ['nullable', 'string'],
            'latitude' => ['nullable', 'numeric', 'between:-90,90'],
            'longitude' => ['nullable', 'numeric', 'between:-180,180'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $validated['is_active'] = $request->boolean('is_active');
        $validated['whatsapp_link'] = $this->generateWhatsappLink($validated['whatsapp_label'] ?? null);
        $validated['email_link'] = $this->generateEmailLink($validated['email_label'] ?? null);

        $contact = Contact::query()->first();

        if ($contact) {
            $contact->update($validated);
        } else {
            Contact::create($validated);
        }

        return redirect()
            ->route('admin.cms.contact.index')
            ->with('success', 'Contact information has been saved successfully.');
    }

    public function storeSocialLink(Request $request)
    {
        $contact = Contact::query()->first();

        if (! $contact) {
            $contact = Contact::create([
                'is_active' => true,
            ]);
        }

        $validated = $request->validate([
            'platform' => ['required', 'in:instagram,tiktok,facebook,youtube,linkedin,other'],
            'label' => ['nullable', 'string', 'max:100'],
            'url' => ['required', 'url', 'max:255'],
            'icon' => ['nullable', 'string', 'max:255'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $validated['contact_id'] = $contact->id;
        $validated['sort_order'] = $validated['sort_order'] ?? 0;
        $validated['is_active'] = $request->boolean('is_active');

        ContactSocialLink::create($validated);

        return redirect()
            ->route('admin.cms.contact.index')
            ->with('success', 'Social link has been created successfully.');
    }

    public function updateSocialLink(Request $request, ContactSocialLink $contactSocialLink)
    {
        $validated = $request->validate([
            'platform' => ['required', 'in:instagram,tiktok,facebook,youtube,linkedin,other'],
            'label' => ['nullable', 'string', 'max:100'],
            'url' => ['required', 'url', 'max:255'],
            'icon' => ['nullable', 'string', 'max:255'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $validated['sort_order'] = $validated['sort_order'] ?? 0;
        $validated['is_active'] = $request->boolean('is_active');

        $contactSocialLink->update($validated);

        return redirect()
            ->route('admin.cms.contact.index')
            ->with('success', 'Social link has been updated successfully.');
    }

    public function destroySocialLink(ContactSocialLink $contactSocialLink)
    {
        $contactSocialLink->delete();

        return redirect()
            ->route('admin.cms.contact.index')
            ->with('success', 'Social link has been deleted successfully.');
    }

    private function generateWhatsappLink(?string $number): ?string
    {
        if (! $number) {
            return null;
        }

        $cleanNumber = preg_replace('/[^0-9]/', '', $number);

        if (str_starts_with($cleanNumber, '0')) {
            $cleanNumber = '62' . substr($cleanNumber, 1);
        }

        return $cleanNumber ? 'https://wa.me/' . $cleanNumber : null;
    }

    private function generateEmailLink(?string $email): ?string
    {
        if (! $email) {
            return null;
        }

        return 'mailto:' . $email;
    }
}
