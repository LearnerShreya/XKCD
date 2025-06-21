<<<<<<< HEAD
# File
=======


# XKCD Email Subscription System

> A PHP-based email subscription platform that sends a daily **random XKCD comic** to verified users. Built using simple file-based storage, this system supports email verification, unsubscribe functionality, and automated CRON-based delivery.

---

## 📌 Project Summary

This project implements a secure and scalable email subscription system without any database — only pure PHP and file handling.

### Core Features:

* Email verification using one-time 6-digit codes
* Daily XKCD comic delivery through a CRON job
* Secure unsubscribe flow via email verification
* All data stored in `registered_emails.txt`
* Modular code inside the `src/` directory only

---

## 📁 Directory Structure

```
project/
│
└── src/
    ├── index.php               # Handles email input & verification
    ├── unsubscribe.php         # Email unsubscribe workflow
    ├── functions.php           # Core functions (email, verification, etc.)
    ├── cron.php                # Runs daily and sends XKCD to users
    ├── setup_cron.sh           # Automates CRON job setup
    └── registered_emails.txt   # File-based user list
```

---

## 🔧 Setup Instructions (How to Run Locally)

> You’ll need PHP 8.3+ and a Unix-like environment (Linux/macOS or WSL on Windows). To test emails locally, you can use tools like MailHog or Mailpit.

---

### Step: Start the PHP Server

Navigate to the `src/` directory and run:

```bash
php -S localhost:8000
```

Visit in browser:

```
http://localhost:8000
```

---

### Step: Test the Flow

1. Enter your email in the form.
2. Check your inbox for the verification code.
3. Enter the code to verify and subscribe.
4. Trigger `cron.php` manually for testing:

```bash
php src/cron.php
```

---

### Step: Configure the CRON Job (Automated)

Run the provided script to schedule daily delivery:

```bash
cd src
chmod +x setup_cron.sh
./setup_cron.sh
```

This sets up a CRON job to run `cron.php` every 24 hours.

---

## ✉️ Email Formats

### Verification Email

**Subject:** `Your Verification Code`
**HTML Body:**

```html
<p>Your verification code is: <strong>123456</strong></p>
```

---

### XKCD Comic Email

**Subject:** `Your XKCD Comic`
**HTML Body:**

```html
<h2>XKCD Comic</h2>
<img src="image_url" alt="XKCD Comic">
<p><a href="unsubscribe.php?email=user@example.com" id="unsubscribe-button">Unsubscribe</a></p>
```

---

### Unsubscribe Confirmation Email

**Subject:** `Confirm Un-subscription`
**HTML Body:**

```html
<p>To confirm un-subscription, use this code: <strong>654321</strong></p>
```

---

## 🧩 Technical Details

| Area            | Details                              |
| --------------- | ------------------------------------ |
| Language        | PHP                                  |
| Storage         | File-based (`registered_emails.txt`) |
| Email Sending   | `mail()` function with HTML content  |
| CRON Scheduling | Via shell script (`setup_cron.sh`)   |
| External API    | XKCD JSON API (`info.0.json`)        |

---