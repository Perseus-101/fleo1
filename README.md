# FLEO

FLEO is a web-based platform designed to help users manage financial information and personal data through an interactive dashboard. The project includes several web pages for handling user signup, login, portfolio management, and more, along with well-organized CSS for styling the interface.

## Table of Contents
- [Project Overview](#project-overview)
- [Technologies Used](#technologies-used)
- [Installation](#installation)
- [Usage](#usage)
- [Folder Structure](#folder-structure)
- [Contributing](#contributing)
- [License](#license)

## Project Overview

This project provides a system to manage financial and personal information securely. It includes features like user authentication, personalized dashboard views, and financial portfolio management.

## Technologies Used

- **HTML**: Structure of the web pages.
- **CSS**: Styling for responsive and visually appealing designs.
- **PHP**: Backend logic for server-side handling.
- **JavaScript**: Frontend interaction.
- **Python**: Specific scripts for additional functionalities.

## Installation

To get a local copy of this project up and running, follow these steps:

### Prerequisites

Make sure you have the following installed:
- PHP 7.x or higher
- A web server (Apache, Nginx, etc.)
- MySQL or similar database
- Python (for additional scripts)

### Steps

1. Clone the repository:
    ```bash
    git clone https://github.com/Perseus-101/fleo1.git
    ```
2. Navigate into the project directory:
    ```bash
    cd fleo1
    ```
3. Set up your web server to serve the files in the `public/` directory.
4. Configure your database connection in `config.php`.

## Usage

Once the installation is complete, you can use the following endpoints:

- `/signup.html` - User registration
- `/login.html` - User login
- `/dashboard.html` - Dashboard for managing personal and financial information
- `/portfolio.html` - View and update financial portfolio

## Folder Structure

```plaintext
fleo1/
│
├── public/               # Main public directory
│   ├── index.html        # Main landing page
│   ├── login.html        # Login page
│   ├── signup.html       # Signup page
│   ├── dashboard.html    # User dashboard
│   └── portfolio.html    # Financial portfolio page
├── css/                  # Stylesheets
│   ├── global.css        # Global styles
│   ├── login.css         # Login page styles
│   ├── dashboard.css     # Dashboard page styles
│   └── portfolio.css     # Portfolio page styles
├── images/               # Project images
├── config.php            # Database configuration
└── README.md             # Project README file
