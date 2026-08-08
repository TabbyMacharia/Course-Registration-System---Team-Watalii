# Course-Registration-System---Team-Watalii
# Student Course Registration System

A Software Engineering laboratory project demonstrating the development of a **Student Course Registration System** using the **Agile Kanban methodology**. The project focuses on collaborative software development, version control using GitHub, and project management using Trello.

---

## Overview
The Student Course Registration System enables students to:

- Log in securely
- View available courses
- Register for courses
- Drop registered courses
- View their registered courses

The project follows Agile Kanban principles to encourage continuous task tracking, collaboration, and incremental development.

---

## 👥 Team Members

| Member | Role |
|--------|------|
| **Tabitha Macharia** | Team Lead, Kanban Manager & Documentation Lead |
| **Esther Kamau** | Business Analyst & QA Tester |
| **Lorna Kyalo** | Database Designer |
| **Ruth Ndua** | UI/UX Designer & Developer |
| **Maxwell Chege** | Lead Developer |

---

## ✨Features

- Student Login
- View Available Courses
- Register for Courses
- Drop Courses
- View Registered Courses

---

## 🛠️Technologies

### Frontend
- HTML5
- CSS3
- JavaScript

### Backend
- PHP

### Database
- MySQL

### Collaboration & Project Management
- Git & GitHub
- Trello

---

## Repository Structure

```
Course-Registration-System---Team-Watalii/
│
├── backend/
│   ├── config/
│   │   └── db.php                  # Database connection settings
│   └── api/
│       ├── login_action.php        # Handles user login and session routing
│       ├── signup_action.php       # Handles student/lecturer registration
│       ├── get_courses.php         # Fetches available courses catalog
│       ├── register_course.php     # Handles student course enrollment
│       ├── add_course_offering.php # Handles lecturer course publishing
│       └── logout.php              # Destroys active user sessions
│
├── frontend/
│   ├── css/
│   │   └── styles.css              # Main design system styles
│   ├── html/
│   │   ├── login.html              # Login page
│   │   ├── signup.html             # Role-based account sign-up page
│   │   ├── courses.html            # Student available courses catalog
│   │   ├── registered.html         # Student enrolled courses view
│   │   └── lecturer.html           # Lecturer course management dashboard
│   └── js/
│       ├── login.js                # Frontend authentication logic
│       ├── signup.js               # Registration form handler
│       ├── courses.js              # Student course listing & registration script
│       ├── registered.js           # Student registered courses script
│       └── lecturer.js             # Lecturer course publishing logic
│
├── database/
│   ├── schema.sql                  # Database table schema
│
├── docs/
│   ├── Requirements Specification
│   ├── ERD
│   ├── Wireframes
│   ├── Test Report
│   └── Reflection Report
│
├── README.md
└── .gitignore
```

---

##Getting Started
###Prerequisites
Install the following software:

- Git
- XAMPP (Apache & MySQL)
- Visual Studio Code (recommended)
- A modern web browser (Chrome, Edge, Firefox)

---

### Installation
bash
git clone https://github.com/TabbyMacharia/Course-Registration-System---Team-Watalii.git

cd Course-Registration-System---Team-Watalii


Move the project folder into your XAMPP 'htdocs' directory if using PHP.

Import the SQL database into phpMyAdmin.

Start Apache and MySQL from the XAMPP Control Panel.

---


### Running the app
1. Start Apache and MySQL in XAMPP.
2. Open phpMyAdmin and import the database.
3. Visit:

```
http://localhost/Course-Registration-System---Team-Watalii/
```

---

## 🗄️ Database
Link to ERD image + brief note (once Lorna delivers)
**Status:** 🚧 In Progress


## 🧪 Testing
Link to test cases/bug report (once Essie delivers)
**Status:** 🚧 In Progress

## 📄 Documentation 
```
Links to be provided later
```
- Requirements Specification
- Use Case Diagram
- Database Design
- UI Wireframes
- Test Report
- Final Report

**Status:** 🚧 In Progress

## 📌 Project Management

Project management is conducted using **Trello** following the **Agile Kanban** methodology.

The workflow consists of:

```
Backlog
    ↓
To Do
    ↓
In Progress
    ↓
Testing
    ↓
Done
```

---

## 🌿 Git Workflow

Each team member works on their own feature branch using the naming convention:

name/role

Examples:

- `tabby/kanban_manager`
- `essie/business_analyst`
- `lorna/database_designer`
- `ruth/uiux_designer`
- `maxxie/lead_developer`

Changes are submitted through Pull Requests before being merged into the `main` branch.

---

## 📜 License

This project is developed for academic purposes as part of a Software Engineering laboratory assignment.
