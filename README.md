<div align="center">

<img src="resources/logo/logo.jpg" alt="CampusHub Logo" width="120">

# 🎓 CampusHub
### Student Services & Event Coordination Platform

**Connecting Students, Campuses, and Opportunities.**

A multi-campus web application designed to simplify student services, event management, announcements, and academic community engagement.

![PHP](https://img.shields.io/badge/PHP-Backend-777BB4?style=for-the-badge&logo=php&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL-Database-4479A1?style=for-the-badge&logo=mysql&logoColor=white)
![Bootstrap](https://img.shields.io/badge/Bootstrap-5.3-7952B3?style=for-the-badge&logo=bootstrap&logoColor=white)
![JavaScript](https://img.shields.io/badge/JavaScript-Frontend-F7DF1E?style=for-the-badge&logo=javascript&logoColor=black)

![HTML5](https://img.shields.io/badge/HTML5-Markup-E34F26?style=flat-square&logo=html5&logoColor=white)
![CSS3](https://img.shields.io/badge/CSS3-Styling-1572B6?style=flat-square&logo=css&logoColor=white)
![Status](https://img.shields.io/badge/Status-Academic_Project-success?style=flat-square)

---

**One Platform. Multiple Campuses. Endless Opportunities.**

</div>

## 📖 About CampusHub

**CampusHub** is a dynamic, database-driven student services and event coordination platform developed using PHP, MySQL, JavaScript, and Bootstrap 5.

The platform provides a centralized digital environment where students can discover campus activities, register for events, access announcements, submit online forms, and stay connected with their academic communities.

CampusHub also includes a multi-level administration system that allows institutional administrators to manage campus information, verify students, organize events, and publish official announcements.

The project demonstrates practical full-stack web development, database management, user authentication, and role-based administration.

## ✨ Key Features

### 🔐 Authentication & Account Management

- Student registration and login
- Session-based authentication
- User logout functionality
- Email verification
- Password recovery and reset
- User profile management
- Profile picture uploads
- Account verification workflow

### 🎓 Student Portal

- Personalized student experience
- Browse campus events
- Register for available events
- Cancel event registrations
- View campus announcements
- Submit online forms
- Explore event media
- Manage personal profile information

### 🏫 Multi-Campus Management

- Support for multiple educational institutions
- Campus-specific student accounts
- Campus-specific events and announcements
- Cross-campus event availability
- Centralized campus administration
- Dedicated campus sub-administrators

### 📅 Event Management

- Create and manage campus events
- Categorize events
- Display event details
- Upload event banners
- Specify event dates and locations
- Set participant limits
- Allow students to register for events
- Support campus-only and open-to-all events
- Maintain event registration records

### 📢 Announcements & Notifications

- Publish official campus announcements
- Display institutional notices
- Support campus-specific announcements
- View announcements across supported institutions
- XML-based announcement data
- DTD-based XML validation

### 📝 Online Forms

- Student form submissions
- Form submission processing
- Administrative form reviews
- Submission status management
- Campus-level form administration

### 🖼️ Multimedia Gallery

- Campus event image gallery
- Event photography
- Media uploads
- Multimedia content management

### 🔎 Search & Filtering

- Search campus events
- Filter events by category
- Browse available activities
- Display campus-relevant results

## 👥 User Roles

CampusHub implements three main user roles.

| Role | Responsibilities |
|------|------------------|
| Main Administrator | Manage campuses, sub-administrators, students, events, and announcements |
| Sub Administrator | Manage assigned campus operations, student verification, events, and forms |
| Student | Browse events, register for activities, view announcements, and manage profiles |

### 👑 Main Administrator

The Main Administrator has centralized management capabilities, including:

- Campus management
- Sub-administrator management
- Student record management
- Event administration
- Official announcement publishing
- System overview dashboard

### 🛡️ Campus Sub Administrator

Each Sub Administrator manages activities associated with their assigned campus.

Responsibilities include:

- Student verification
- Campus event management
- Campus announcements
- Online form reviews
- Student record monitoring

### 👨‍🎓 Student

Students can access the platform to:

- Create and manage accounts
- Discover university events
- Register and unregister for events
- View official announcements
- Submit forms
- Explore the campus gallery

## 🛠️ Technology Stack

| Technology | Purpose |
|------------|---------|
| HTML5 | Web page structure |
| CSS3 | Custom styling |
| Bootstrap 5.3 | Responsive user interface |
| JavaScript | Client-side interactivity |
| AJAX | Asynchronous communication |
| PHP | Server-side application logic |
| MySQL | Relational database |
| MySQLi | PHP database connectivity |
| PHPMailer | Email delivery |
| XML | Structured announcement data |
| DTD | XML document validation |
| Bootstrap Icons | User interface icons |

## 🏗️ Project Structure

```text
Campus-Hub/
│
├── css/
│   └── style.css
│
├── js/
│   └── script.js
│
├── includes/
│   ├── PHPMailer/
│   ├── connection.php
│   ├── header.php
│   ├── footer.php
│   └── mailer.php
│
├── process/
│   ├── signInProcess.php
│   ├── signUpProcess.php
│   ├── signOutProcess.php
│   ├── verifyEmailCodeProcess.php
│   ├── forgotPasswordProcess.php
│   ├── resetPasswordProcess.php
│   ├── verifyStudentProcess.php
│   ├── registerEventProcess.php
│   ├── unregisterEventProcess.php
│   ├── addEventProcess.php
│   ├── addAnnouncementProcess.php
│   ├── manageCampusProcess.php
│   ├── manageSubAdminProcess.php
│   ├── updateProfileProcess.php
│   ├── uploadMediaProcess.php
│   └── ...
│
├── resources/
│   ├── carousel_images/
│   ├── event_images/
│   ├── logo/
│   └── profile_images/
│
├── index.php
├── home.php
├── events.php
├── event_detail.php
├── announcements.php
├── gallery.php
├── profile.php
├── submit_form.php
├── admin_dashboard.php
├── sub_admin_dashboard.php
├── xml_validator.php
│
├── announcements.xml
├── announcements.dtd
│
├── campushub.sql
├── campushub.mwb
│
└── README.md
```

## 🚀 Installation & Setup

Follow the instructions below to configure and run CampusHub locally.

### 1️⃣ Prerequisites

Ensure that the following software is available:

- PHP with MySQLi support
- MySQL Server
- Apache Web Server
- XAMPP or an equivalent local PHP environment
- A modern web browser
- A code editor such as Visual Studio Code

### 2️⃣ Clone the Repository

Open a terminal and run:

```bash
git clone https://github.com/Demon-inz4ne/Campus-Hub.git
```

Alternatively, download the repository as a ZIP file.

### 3️⃣ Configure the Local Server

If using XAMPP:

1. Install XAMPP.
2. Open the XAMPP Control Panel.
3. Start Apache.
4. Start MySQL.
5. Copy the project folder into the `htdocs` directory.

Example:

```text
C:\xampp\htdocs\Campus-Hub\
```

### 4️⃣ Database Setup

1. Open phpMyAdmin.
2. Navigate to the Import section.
3. Select the database script:

```text
campushub.sql
```

4. Execute the script.

The SQL script creates the `campushub` database, required tables, and sample records.

**Database Name:** `campushub`

> Note: The SQL script contains DROP TABLE statements. Import it into a fresh development environment to avoid overwriting existing data.

### 5️⃣ Configure Database Connection

Open:

```text
includes/connection.php
```

Update the MySQL connection settings for your local development environment.

Example:

```php
Database::$connection = new mysqli(
    "localhost",
    "root",
    "YOUR_DB_PASSWORD",
    "campushub",
    "3306"
);
```

Replace `YOUR_DB_PASSWORD` with your local MySQL password.

For better security, move database credentials into environment-based configuration before deployment.

### 6️⃣ Configure Email Services

CampusHub uses PHPMailer for email-related functionality.

The mail configuration is located in:

```text
includes/mailer.php
```

Configure an authorized SMTP account to enable verification emails and other supported email features.

Never commit real SMTP credentials, app passwords, or access tokens to GitHub.

### 7️⃣ Launch CampusHub

Open your browser and navigate to:

```text
http://localhost/Campus-Hub/
```

The CampusHub login and registration interface should appear.

## 🗃️ Database Design

CampusHub uses a relational MySQL database to manage institutional information and user activities.

Major database entities include:

- Users
- User Types
- Campuses
- Account Statuses
- Event Categories
- Events
- Event Registrations
- Announcements
- Online Form Submissions

The database supports relationships between students, institutions, campus events, and administrative operations.

**Database Script:**

```text
campushub.sql
```

**Database Design File:**

```text
campushub.mwb
```

## 🔄 Application Workflow

### Student Workflow

```text
Student Registration
        ↓
Account Verification
        ↓
Student Login
        ↓
Browse Campus Events
        ↓
View Event Details
        ↓
Register for Events
        ↓
Access Other Student Services
```

### Administration Workflow

```text
Administrator Login
        ↓
Role Verification
        ↓
Administrator Dashboard
        ↓
Manage Campus Operations
        ↓
Review Students / Forms
        ↓
Manage Events & Announcements
```

## 🎯 Project Objectives

The primary objectives of CampusHub are to:

1. Centralize student services and campus information.
2. Simplify event organization and registration.
3. Improve communication between students and administrators.
4. Support multiple educational institutions.
5. Digitize student verification and form submissions.
6. Reduce manual administrative work.
7. Provide a responsive and accessible web interface.
8. Demonstrate full-stack web development and database integration.

## 🔒 Security Considerations

CampusHub is an academic project and requires additional security improvements before production deployment.

Recommended improvements include:

- Password hashing using PHP's `password_hash()` and `password_verify()`
- Environment-based database and SMTP configuration
- Prepared statements for database operations
- Input validation and output escaping
- CSRF protection for state-changing requests
- Secure session and cookie configuration
- Restricted file upload handling
- HTTPS deployment
- Stronger authorization checks
- Removal of development credentials and sensitive test data

The project should be reviewed and hardened before deployment with real student information.

## 🔮 Future Enhancements

Potential improvements include:

- Real-time student notifications
- Campus discussion forums
- Event QR-code registration
- Attendance tracking
- Student club management
- Academic calendar integration
- Advanced dashboard analytics
- Improved search and filtering
- Mobile-friendly progressive web app support
- Enhanced account security

## 🎓 Academic Project Information

**Project Name:** CampusHub

**Project Type:** Full-Stack Web Application

**Project Category:** Student Services & Event Management

**Backend:** PHP

**Frontend:** HTML, CSS, Bootstrap, JavaScript

**Database:** MySQL

**Purpose:** Academic and portfolio demonstration

## 👨‍💻 Developer

**Janith Geeshan**

Software Engineering Undergraduate

Java Institute for Advanced Technology  
Sri Lanka 🇱🇰

**GitHub:** [@Demon-inz4ne](https://github.com/Demon-inz4ne)

**Repository:** [Campus-Hub](https://github.com/Demon-inz4ne/Campus-Hub)

## 📌 Disclaimer

CampusHub was developed primarily for educational purposes.

The application demonstrates full-stack web development, relational database design, user management, event coordination, and multi-level administration.

The included sample information is intended for development and demonstration.

Additional security improvements, testing, and configuration are required before production use.

---

<div align="center">

### 🎓 CampusHub

**Connecting Students, Campuses, and Opportunities.**

Designed & Developed by **Janith Geeshan**

Made with ❤️ and PHP.

⭐ Star this repository if you find the project interesting!

</div>
