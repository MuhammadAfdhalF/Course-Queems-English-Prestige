# 🎓 Queens English Prestige – E-Course Platform

A web-based e-learning platform developed for **Queens English Prestige** to support English course learning activities digitally.

This platform is built using **Laravel** with a modern and responsive interface powered by **Tailwind CSS**. The system helps manage the full learning process, starting from course information, student registration, course ordering, learning access, practice and final exam submission, certificate generation, testimonials, and admin management.

---

## 📌 About the Project

**Queens English Prestige – E-Course Platform** is designed to help an English course institution manage its learning and operational activities in a more structured, efficient, and centralized way.

Through this application, visitors can explore course information, read institution details, take free tests, and register as students. After logging in, students can order courses, access learning materials after approval, complete modules, submit practice tests and final exams, view results, download certificates, and submit testimonials.

On the admin side, the system provides a complete management panel to handle courses, students, orders, payments, revenue reports, course access, certificates, testimonials, notifications, and public website content.

---

## 🛠️ Technologies

- ⚙️ **Laravel 12** → backend framework
- 🐘 **PHP 8.3** → server-side programming
- 🗄️ **MySQL** → relational database
- 🎨 **Tailwind CSS** → responsive user interface
- ⚡ **Alpine.js** → lightweight frontend interactivity
- 📦 **Vite** → frontend asset bundler
- 📄 **Laravel DomPDF** → certificate PDF generation
- 🔳 **Simple QR Code** → certificate verification QR code
- 🔐 **Custom Authentication** → login, register, and role-based access control

---

## 🌐 System Architecture

### Public Website

The public website is used by visitors to explore the institution and available English courses.

Main features:

- Homepage
- About page
- Course listing
- Course detail page
- Free test
- News and information
- Contact page
- Certificate verification
- Homepage testimonial carousel

---

### Student Portal

The student portal is used by registered students to access and complete their learning activities.

Main features:

- Student dashboard
- Profile management
- All courses page
- My courses page
- Learning path
- Module materials
- Module completion
- Practice submission
- Final exam submission
- Assessment result
- Certificate download
- Testimonial submission
- Student notifications

---

### Admin Panel

The admin panel is used to manage the operational side of the e-course system.

Main features:

- Admin dashboard
- Student management
- Course management
- Order management
- Payment management
- Revenue report
- Course access management
- Practice and final exam review
- Certificate management
- Testimonial management
- CMS management
- Notification management
- Admin profile/settings

---

## ⚙️ Main Features

- 🏠 **Public Website**  
  Displays institution information, course programs, free tests, news, contact information, and testimonials.

- 👤 **Authentication & Role Management**  
  Custom login and registration system with **admin** and **student** roles.

- 📚 **Course Management**  
  Admin can manage course programs, course levels, modules, materials, practices, and final exams.

- 🛒 **Course Order System**  
  Students can order courses directly from the course detail page.

- 💳 **Manual Payment Recording**  
  Payments are recorded manually by the admin before an order is approved.

- 🔓 **Course Access Management**  
  Students receive course access after their order is approved or through manual access granted by the admin.

- 📖 **Student Learning Flow**  
  Students can access learning modules, read materials, complete practices, and take final exams.

- 📝 **Practice & Final Exam Review**  
  Admin can review student practice submissions and final exam attempts.

- 🎓 **Certificate Management**  
  The system can generate digital certificates with PDF download and QR code verification.

- 💬 **Testimonial Management**  
  Students can submit testimonials, and admin can display selected testimonials on the homepage.

- 🔔 **Notification System**  
  Admin and students receive notifications for important system activities.

- 📊 **Dashboard & Revenue Report**  
  Admin can monitor students, orders, payments, revenue, certificates, and recent activities.

- 🧩 **CMS Management**  
  Admin can manage public website content such as homepage, about page, FAQ, mentors, contact, news, gallery, and free tests.

---

## 🔄 Main Application Flow

```text
Visitor opens the website
↓
Visitor views course information
↓
Visitor registers as a student
↓
Student logs in
↓
Student orders a course
↓
Admin reviews the order
↓
Admin records the payment
↓
Admin approves the order
↓
Course access is created automatically
↓
Student starts learning
↓
Student completes modules, practice tests, and final exam
↓
Admin reviews the assessment
↓
Certificate is issued
↓
Student submits a testimonial
↓
Admin can display the testimonial on the homepage
```

---

## 👥 User Roles

### 👨‍🎓 Student

Students can:

- Register and log in
- Manage their profile
- View available courses
- Order courses
- Access enrolled courses
- Read module materials
- Complete learning modules
- Submit practice tests
- Submit final exams
- View assessment results
- Download certificates
- Submit testimonials
- Receive notifications

---

### 👨‍💼 Admin

Admins can:

- Manage the dashboard
- Manage students
- Manage course programs and course levels
- Manage modules and materials
- Manage practices and final exams
- Review student submissions
- Manage course orders
- Record payments
- View revenue reports
- Manage course access
- Manage certificates
- Manage testimonials
- Manage notifications
- Manage public website content through CMS

---

## 📊 Dashboard & Reports

The admin dashboard provides a real-time overview of important system data, including:

- Total students
- Active enrollments
- Active courses
- Pending orders
- Waiting reviews
- Issued certificates
- Locked certificates
- Monthly revenue
- Recent transactions
- Recent activities
- Action center

The revenue report provides financial insights, including:

- Total revenue
- Today revenue
- This month revenue
- Payment count
- Monthly revenue trend
- Revenue by payment method
- Revenue by course

---

## 🎓 Certificate & Verification

The certificate feature supports:

- Certificate preview
- Certificate PDF download
- QR code verification
- Public certificate verification page
- Certificate status management
- Global signature setting

Certificates can be verified through a public verification page using a verification token or QR code.

---

## 💬 Testimonials

The system supports two types of testimonials:

- **Course Testimonial**  
  Used after a student completes a course.

- **Company Testimonial**  
  Used as a general testimonial for the institution.

Admin can manage homepage testimonial visibility using **Show on Home** and **Hide from Home** actions.

---

## 📈 Impact

✅ **Operational Efficiency**  
Course, student, order, payment, and certificate management are handled in one centralized system.

✅ **Structured Learning Process**  
Students can follow a clear learning path from modules, practice tests, final exams, and certificates.

✅ **Better Monitoring**  
Admin can monitor orders, payments, revenue, student progress, and system activities through the dashboard.

✅ **Verified Digital Certificates**  
Certificates can be downloaded as PDF and verified using QR code.

✅ **Professional Online Presence**  
The public website, course information, testimonials, and CMS help improve the institution’s digital branding.

---


---

## 🙋‍♂️ Author

**Muhammad Afdhal F**

📧 Email: cuyafdal@gmail.com  
📷 Instagram: @holla.cuy  
💼 LinkedIn: Muhammad Afdhal F  

🧠 Passionate in Web Development, Mobile Development, QA Automation, and AI.

```text
Backend    : Laravel
Frontend   : Blade, Tailwind CSS, Alpine.js
Database   : MySQL
PDF        : Laravel DomPDF
QR Code    : Simple QR Code
```
