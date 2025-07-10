# Alpzo - Local Web Development Environment Manager

<p align="center">
  <strong>A free and open-source web development tools management platform designed for Windows</strong>
</p>

> **Language**: 🇺🇸 English | [🇹🇷 Türkçe](README.tr.md)

## 📋 Table of Contents

- [Introduction](#introduction)
- [Why Alpzo?](#why-alpzo)
- [Key Features](#key-features)
- [Installation and Getting Started](#installation-and-getting-started)
- [User Guide](#user-guide)
    - [PHP Management](#php-management)
    - [NodeJS Management](#nodejs-management)
    - [Project Management](#project-management)
    - [Path Management](#path-management)
- [Security and Permissions](#security-and-permissions)
- [Frequently Asked Questions](#frequently-asked-questions)
- [Roadmap](#roadmap)
- [License](#license)

## 🎯 Introduction

Alpzo is a comprehensive tool designed to help web developers easily manage their local development environments. It allows you to manage essential technologies like PHP, MySQL, Nginx, and NodeJS from a single interface. Currently, it is developed exclusively for Windows operating systems.

## 💡 Why Alpzo?

The motivation for developing Alpzo stems from the shortcomings of existing solutions:

### Issues with Current Solutions:
- **Outdated Software**: Most alternatives on the market are outdated or don't receive regular updates
- **High Costs**: Current and functional applications are often paid
- **Insufficient Support**: Free alternatives have limited or no technical support
- **Poor User Experience**: Complex interfaces and non-user-friendly designs
- **Lack of Customization**: Rigid structures that cannot be adapted to user needs

### Alpzo's Solution:
- ✅ **Completely Free**: No hidden fees or premium features
- ✅ **Open Source**: Code is open to everyone, customizable to your needs
- ✅ **Modern and User-Friendly**: Simple and intuitive interface design
- ✅ **Active Development**: Regular updates and new features

## ✨ Key Features

### 🚀 Automatic Startup System

Alpzo automatically performs the following operations on each startup:

1. **System Check**
    - Checks for the existence of critical directories
    - Automatically creates missing directories
    - Validates configuration files

2. **Service Startup**
    - Starts MySQL database service
    - Activates installed PHP versions
    - Automatically assigns ports to each PHP version (starting from 9000)
    - Starts Nginx web server

3. **Project Scanning**
    - Scans projects in defined directories
    - Automatically detects new projects
    - Creates necessary configurations according to default settings

> **Tip**: The automatic scanning feature can be disabled from the settings page for performance reasons.

### 📦 PHP Management

PHP management is one of Alpzo's most powerful features:

#### Supported Features:

**1. Version Management**
- Supports all PHP 7.x and later versions
- One-click PHP version download and installation
- Ability to use multiple PHP versions simultaneously

**2. Configuration Management**
- Visual php.ini editor
- Automatic backup before each change
- Change history and rollback functionality

**3. Port Management**
- Automatic port assignment (starts from 9000, +1 for each version)
- Automatic detection and resolution of port conflicts

**4. Service Control**
- Start/stop PHP-FPM services
- Bulk service management
- Display active PHP modules

**5. Project Association**
- Ability to use different PHP versions for each project
- List projects linked to PHP versions
- Safe deletion (versions in use cannot be deleted)

### 🟢 NodeJS Management

NodeJS management is simple and effective:

- List all NodeJS versions
- Download and install desired versions
- Quick switching between versions
- Clean up unused versions

### 📁 Project Management

Alpzo's project management features are designed for modern web development needs:

#### Supported Project Types:
- **Backend**: Laravel, Blank PHP
- **Frontend**: Vue, React, Angular, Svelte
- **Full-Stack**: Nuxt.js, Next.js
- **Build Tools**: Vite

#### Project Features:

**1. Project Creation**
- One-click project creation in defined directories
- Automatic project configuration
- Turkish character control (for URL compatibility)

**2. URL Management**
- Automatic URL generation based on project name
- Automatic addition to Windows hosts file
- SSL certificate support (for development purposes)

**3. Favorites System**
- Add frequently used projects to favorites
- Quick access to favorite projects on the homepage

**4. Log Management**
- Separate Nginx logs for each project
- Store error and access logs in separate files
- View and clean log files

**5. Project Deletion**
- Safe deletion confirmation
- Complete removal of project files
- Clean up Nginx configuration
- Delete related log files

**6. Filtering and Search**
- Search by project name
- Filter by PHP/NodeJS version
- List by favorite status
- Group by path

### 🗂️ Path (Directory) Management

Path management allows you to organize your projects in different locations:

**Features:**
- Define multiple project directories
- Set default directory
- List and edit directories
- Automatic www folder creation on first installation

**Usage Scenarios:**
- Manage projects on different disks or partitions
- Organize client projects in separate directories
- Separate test and production-like environments

## 🔧 Installation and Getting Started

> **Requirement**: Windows 10 (64-bit) or later

### Installation Steps:

1. **Download**
    - Download the latest version from the [Releases](https://github.com/alpzo/alpzo/releases) page
    - Run the `.exe` installation file

2. **Installation Wizard**
    - Choose installation directory
    - Create Start menu shortcuts
    - Add desktop shortcut (optional)

3. **First Run**
    - Run Alpzo **as administrator**
    - The first installation wizard will open
    - Set your default project directory

## 📖 User Guide

### PHP Management

#### Adding a New PHP Version:
1. Go to the "PHP Management" tab from the left menu
2. Click the "Add New Version" button
3. Select the desired PHP version
4. Click the "Download and Install" button
5. PHP will start automatically after installation is complete

#### Editing php.ini:
1. Click the "Settings" icon next to the PHP version you want to edit
2. Select the "Edit php.ini" option
3. Make your changes in the visual editor
4. Click the "Save" button (automatic backup is taken)

### NodeJS Management

1. Go to the "NodeJS Management" tab
2. View existing versions
3. Use the "Add Version" button to add a new version
4. Select the relevant version to change the active version

### Project Management

#### Creating a New Project:
1. Go to the "Projects" tab or use the quick access on the homepage
2. Click the "New Project" button
3. Enter project information:
    - Project name (don't use Turkish characters)
    - Project type (Laravel, React, etc.)
    - PHP/NodeJS version
    - Project directory
4. Click the "Create" button

#### Project Management Tips:
- Mark your favorite projects with the star icon
- Use filtering features to quickly find your projects
- Regularly check log files

### Path Management

1. Go to the "Settings" page
2. Find the "Path Management" section
3. Add directories with "Add New Path"
4. Use the star icon to set the default directory

## 🔒 Security and Permissions

### Administrator Permissions

Alpzo **must be run as administrator** because some features require access to Windows system files:

- **Editing hosts file**: To add project URLs
- **Service management**: To start MySQL and Nginx services
- **Port listening**: To open necessary ports for web server

### Security Recommendations:
1. Only download Alpzo from trusted sources
2. Regularly backup your projects
3. Do not use for production environments (development purposes only)

## ❓ Frequently Asked Questions

**Q: Is Alpzo free?**
A: Yes, Alpzo is completely free and open source. There are no hidden fees.

**Q: Is there Mac or Linux support?**
A: Currently, only Windows is supported. Support for other platforms is on our roadmap.

**Q: Can multiple PHP versions run simultaneously?**
A: Yes, each PHP version runs on different ports and can be active simultaneously.

**Q: Can I migrate my existing projects to Alpzo?**
A: Yes, simply copy your projects to the defined directories. Alpzo will automatically detect them.

## 🗺️ Roadmap

### Upcoming Features
- ✨ Manual port change support

> **Note**: The roadmap may be updated based on the development process and community feedback.

## 📄 License

This project is licensed under the [MIT License](LICENSE). See the LICENSE file for detailed information.

---

<p align="center">
  <strong>Alpzo</strong> - Manage your development environment professionally! 🚀
</p>

<p align="center">
  <a href="https://github.com/alpzo/alpzo/issues">Report Issue</a> •
  <a href="https://github.com/alpzo/alpzo/discussions">Discussions</a> •
  <a href="https://discord.gg/alpzo">Discord</a>
</p>
