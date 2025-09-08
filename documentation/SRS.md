# Software Requirements Specification (SRS)
## NIMR Intranet System

**Document Version**: 2.0.0  
**Date**: September 8, 2025  
**Project**: NIMR Intranet Management System  
**Client**: National Institute for Medical Research (NIMR)  
**Development Team**: NIMR IT Department  

---

## Table of Contents

1. [Introduction](#1-introduction)
2. [Overall Description](#2-overall-description)
3. [System Features](#3-system-features)
4. [External Interface Requirements](#4-external-interface-requirements)
5. [Non-Functional Requirements](#5-non-functional-requirements)
6. [Other Requirements](#6-other-requirements)
7. [Appendices](#7-appendices)

---

## 1. Introduction

### 1.1 Purpose
This Software Requirements Specification (SRS) document describes the functional and non-functional requirements for the NIMR Intranet System. The system is designed to facilitate internal communication, document management, and organizational announcements within the National Institute for Medical Research.

### 1.2 Scope
The NIMR Intranet System is a web-based application that provides:
- Centralized document management with department-wise organization
- Announcement and news broadcasting system
- User management with role-based access control
- Organizational hierarchy management
- Internal communication platform

### 1.3 Definitions, Acronyms, and Abbreviations

| Term | Definition |
|------|------------|
| NIMR | National Institute for Medical Research |
| SRS | Software Requirements Specification |
| UI | User Interface |
| API | Application Programming Interface |
| RBAC | Role-Based Access Control |
| HQ | Headquarters |
| Centre | Regional research centers under NIMR |
| Department | Functional departments within NIMR |

### 1.4 References
- NIMR Organizational Structure Documentation
- Laravel Framework Documentation (v12.x)
- Web Content Accessibility Guidelines (WCAG) 2.1
- ISO/IEC 25010:2011 - Systems and Software Quality Models

### 1.5 Overview
This document is structured to provide a comprehensive understanding of the system requirements, starting with an overall description, followed by specific functional requirements, interface requirements, and quality attributes.

---

## 2. Overall Description

### 2.1 Product Perspective
The NIMR Intranet System is a standalone web application designed to replace manual document circulation and improve internal communication efficiency. The system integrates with existing NIMR infrastructure while maintaining data security and organizational compliance.

### 2.2 Product Functions
The major functions of the system include:

**Document Management:**
- Upload, categorize, and organize documents by department
- Version control and document lifecycle management
- Search and filter capabilities
- Download tracking and analytics

**Communication Management:**
- Create and broadcast announcements
- News and updates distribution
- Internal messaging capabilities

**User and Access Management:**
- User registration and authentication
- Role-based access control (Super Admin, Admin, User)
- Organizational hierarchy management

**Administrative Functions:**
- System configuration and settings
- User activity monitoring
- Report generation and analytics

### 2.3 User Classes and Characteristics

#### 2.3.1 Super Administrator
- **Characteristics**: IT staff with full system access
- **Responsibilities**: System configuration, user management, security oversight
- **Technical Expertise**: High
- **Usage Frequency**: Daily

#### 2.3.2 Administrator
- **Characteristics**: Department heads, senior managers
- **Responsibilities**: Content management, user oversight within department
- **Technical Expertise**: Medium
- **Usage Frequency**: Daily

#### 2.3.3 Regular User
- **Characteristics**: NIMR staff members
- **Responsibilities**: Document access, announcement viewing
- **Technical Expertise**: Basic to Medium
- **Usage Frequency**: Daily

### 2.4 Operating Environment

**Client-Side Requirements:**
- Web browser: Chrome 90+, Firefox 88+, Safari 14+, Edge 90+
- JavaScript enabled
- Minimum screen resolution: 1024x768
- Internet connection

**Server-Side Requirements:**
- Operating System: Ubuntu 20.04 LTS or CentOS 8+
- Web Server: Nginx 1.18+ or Apache 2.4+
- PHP: Version 8.1+
- Database: MySQL 8.0+ or MariaDB 10.3+
- Storage: Minimum 50GB for documents and system files

### 2.5 Design and Implementation Constraints

**Regulatory Constraints:**
- Compliance with Tanzania data protection laws
- NIMR information security policies
- Government IT standards and guidelines

**Technical Constraints:**
- Must work on existing NIMR network infrastructure
- Limited to web-based technologies
- Must support mobile responsive design
- Integration with existing email systems

**Business Constraints:**
- Budget limitations for hosting and maintenance
- Phased rollout across different NIMR centers
- Training requirements for non-technical users

### 2.6 Assumptions and Dependencies

**Assumptions:**
- Users have basic computer literacy
- Reliable internet connectivity at all NIMR locations
- IT support available for system maintenance
- Regular content updates by designated staff

**Dependencies:**
- NIMR network infrastructure availability
- Email server for notifications
- Backup and disaster recovery systems
- SSL certificate for secure communications

---

## 3. System Features

### 3.1 User Authentication and Authorization

#### 3.1.1 Description
Secure user login system with role-based access control ensuring appropriate access levels for different user types.

#### 3.1.2 Priority: High

#### 3.1.3 Functional Requirements

**REQ-AUTH-001**: The system shall provide secure user authentication using email and password.

**REQ-AUTH-002**: The system shall implement role-based access control with three levels:
- Super Administrator: Full system access
- Administrator: Department-level management access
- User: Read-only access with limited upload permissions

**REQ-AUTH-003**: The system shall enforce password complexity requirements:
- Minimum 8 characters
- At least one uppercase letter
- At least one lowercase letter
- At least one number

**REQ-AUTH-004**: The system shall provide session management with automatic timeout after 8 hours of inactivity.

**REQ-AUTH-005**: The system shall log all authentication attempts for security auditing.

### 3.2 Document Management

#### 3.2.1 Description
Comprehensive document management system allowing upload, organization, and retrieval of documents with proper categorization and access controls.

#### 3.2.2 Priority: High

#### 3.2.3 Functional Requirements

**REQ-DOC-001**: The system shall allow administrators to upload documents with the following supported formats:
- PDF (.pdf)
- Microsoft Word (.doc, .docx)
- Microsoft Excel (.xls, .xlsx)
- Microsoft PowerPoint (.ppt, .pptx)
- Images (.jpg, .jpeg, .png, .gif)

**REQ-DOC-002**: The system shall categorize documents by department:
- Executive Office
- Human Resources
- Information Technology
- Finance and Administration
- Research Operations
- Public Health
- Laboratory Services

**REQ-DOC-003**: The system shall enforce file size limits:
- Maximum individual file size: 50MB
- Maximum total storage per department: 5GB

**REQ-DOC-004**: The system shall provide document search functionality by:
- Document title
- File type
- Department
- Upload date
- Keywords in document content (where possible)

**REQ-DOC-005**: The system shall track document downloads and provide analytics on:
- Download count per document
- Most popular documents
- Department-wise usage statistics

**REQ-DOC-006**: The system shall provide two viewing modes:
- Department Cards View: Overview of all departments with document counts
- List View: Detailed document listing with filters

**REQ-DOC-007**: The system shall restrict document uploads to administrators only.

**REQ-DOC-008**: The system shall provide document version control allowing administrators to update existing documents while maintaining history.

### 3.3 Announcement Management

#### 3.3.1 Description
System for creating, managing, and displaying organizational announcements and news to all users.

#### 3.3.2 Priority: High

#### 3.3.3 Functional Requirements

**REQ-ANN-001**: The system shall allow administrators to create announcements with:
- Title (required, maximum 200 characters)
- Content (required, rich text format)
- Publication date
- Expiration date (optional)
- Target audience (All users, specific departments, specific roles)

**REQ-ANN-002**: The system shall display announcements on the dashboard in chronological order with most recent first.

**REQ-ANN-003**: The system shall provide announcement categories:
- General Information
- Policy Updates
- Events and Meetings
- System Maintenance
- Emergency Notifications

**REQ-ANN-004**: The system shall send email notifications for critical announcements to all targeted users.

**REQ-ANN-005**: The system shall automatically archive expired announcements while maintaining searchable history.

**REQ-ANN-006**: The system shall allow administrators to edit or delete announcements with proper audit logging.

### 3.4 User Management

#### 3.4.1 Description
Administrative interface for managing user accounts, roles, and organizational assignments.

#### 3.4.2 Priority: High

#### 3.4.3 Functional Requirements

**REQ-USER-001**: The system shall allow super administrators to create, modify, and deactivate user accounts.

**REQ-USER-002**: The system shall maintain user profiles with:
- Full name (required)
- Email address (required, unique)
- Role assignment
- Department assignment
- Centre/Headquarters assignment
- Contact information (optional)
- Profile picture (optional)

**REQ-USER-003**: The system shall provide user import functionality via CSV file upload with validation.

**REQ-USER-004**: The system shall send welcome emails to new users with login credentials.

**REQ-USER-005**: The system shall provide user activity reports showing:
- Last login date
- Document download history
- System usage patterns

**REQ-USER-006**: The system shall allow users to update their own profile information except role and department assignments.

### 3.5 Organizational Hierarchy Management

#### 3.5.1 Description
Management of NIMR's organizational structure including headquarters, centers, and departments.

#### 3.5.2 Priority: Medium

#### 3.5.3 Functional Requirements

**REQ-ORG-001**: The system shall maintain a hierarchical structure with:
- Headquarters (parent level)
- Centres (child of headquarters)
- Departments (child of centres/headquarters)

**REQ-ORG-002**: The system shall allow super administrators to add, modify, or deactivate organizational units.

**REQ-ORG-003**: The system shall enforce organizational hierarchy rules preventing circular references.

**REQ-ORG-004**: The system shall provide organizational charts and reporting structures.

**REQ-ORG-005**: The system shall maintain historical records of organizational changes.

### 3.6 Dashboard and Analytics

#### 3.6.1 Description
Comprehensive dashboard providing overview of system usage, statistics, and quick access to key functions.

#### 3.6.2 Priority: Medium

#### 3.6.3 Functional Requirements

**REQ-DASH-001**: The system shall provide a personalized dashboard showing:
- Recent announcements
- Popular documents
- Quick access shortcuts
- Personal activity summary

**REQ-DASH-002**: The system shall display system-wide statistics for administrators:
- Total documents by department
- Recent uploads
- User activity metrics
- Storage usage

**REQ-DASH-003**: The system shall provide downloadable reports in PDF and Excel formats.

**REQ-DASH-004**: The system shall update dashboard metrics in real-time.

### 3.7 Search and Navigation

#### 3.7.1 Description
Advanced search capabilities and intuitive navigation throughout the system.

#### 3.7.2 Priority: Medium

#### 3.7.3 Functional Requirements

**REQ-SEARCH-001**: The system shall provide global search functionality across:
- Documents (titles and metadata)
- Announcements
- User names (for administrators)

**REQ-SEARCH-002**: The system shall support advanced filtering options:
- Date ranges
- File types
- Departments
- Document categories

**REQ-SEARCH-003**: The system shall provide search result highlighting and relevance ranking.

**REQ-SEARCH-004**: The system shall maintain search history for logged-in users.

**REQ-SEARCH-005**: The system shall provide keyboard shortcuts for common actions:
- Ctrl+K: Quick search
- Ctrl+D: Dashboard
- Ctrl+U: Upload (administrators only)

---

## 4. External Interface Requirements

### 4.1 User Interfaces

#### 4.1.1 General UI Requirements

**REQ-UI-001**: The system shall provide a responsive web interface compatible with desktop, tablet, and mobile devices.

**REQ-UI-002**: The system shall follow modern web design principles with:
- Clean, professional appearance
- Consistent navigation
- Intuitive user workflow
- Accessibility compliance (WCAG 2.1 Level AA)

**REQ-UI-003**: The system shall use a consistent color scheme reflecting NIMR branding:
- Primary: Blue (#1e40af)
- Secondary: Gray (#6b7280)
- Success: Green (#059669)
- Warning: Yellow (#d97706)
- Danger: Red (#dc2626)

#### 4.1.2 Specific Interface Requirements

**REQ-UI-004**: Login page shall include:
- Email and password fields
- "Remember me" option
- Password reset link
- NIMR logo and branding

**REQ-UI-005**: Dashboard shall display:
- Navigation menu (sidebar or top bar)
- Welcome message with user name
- Recent announcements panel
- Quick statistics cards
- Quick action buttons

**REQ-UI-006**: Document management interface shall provide:
- Department selection tabs/cards
- File upload area (drag-and-drop enabled)
- Document listing with sorting options
- Search and filter controls
- Breadcrumb navigation

### 4.2 Hardware Interfaces

**REQ-HW-001**: The system shall be accessible through standard web browsers without requiring additional hardware.

**REQ-HW-002**: The system shall support file upload from local storage devices accessible to the client computer.

### 4.3 Software Interfaces

#### 4.3.1 Email Integration

**REQ-SW-001**: The system shall integrate with SMTP email servers for:
- User registration notifications
- Password reset emails
- Critical announcement notifications
- System alerts

**REQ-SW-002**: Email integration shall support:
- TLS/SSL encryption
- Authentication via username/password
- Configurable SMTP settings

#### 4.3.2 Database Interface

**REQ-SW-003**: The system shall interface with MySQL/MariaDB database for:
- User data storage
- Document metadata
- System configuration
- Audit logs

#### 4.3.3 File System Interface

**REQ-SW-004**: The system shall interface with the server file system for:
- Document storage
- Temporary file processing
- Log file management
- Backup operations

### 4.4 Communication Interfaces

**REQ-COMM-001**: The system shall communicate over HTTPS protocol for all user interactions.

**REQ-COMM-002**: The system shall support RESTful API architecture for potential future integrations.

**REQ-COMM-003**: The system shall implement secure session management using encrypted cookies.

---

## 5. Non-Functional Requirements

### 5.1 Performance Requirements

**REQ-PERF-001**: The system shall load the main dashboard within 3 seconds under normal network conditions.

**REQ-PERF-002**: The system shall support concurrent access by up to 200 users without performance degradation.

**REQ-PERF-003**: Document search results shall be returned within 2 seconds for queries across the entire database.

**REQ-PERF-004**: File uploads shall support files up to 50MB with progress indicators for uploads exceeding 5MB.

**REQ-PERF-005**: The system shall maintain response times under 5 seconds for 95% of user requests.

### 5.2 Security Requirements

**REQ-SEC-001**: The system shall encrypt all data transmission using TLS 1.2 or higher.

**REQ-SEC-002**: The system shall store passwords using strong hashing algorithms (bcrypt with cost factor 12+).

**REQ-SEC-003**: The system shall implement protection against common web vulnerabilities:
- SQL Injection
- Cross-Site Scripting (XSS)
- Cross-Site Request Forgery (CSRF)
- File upload vulnerabilities

**REQ-SEC-004**: The system shall maintain audit logs for:
- User authentication attempts
- Administrative actions
- Document uploads/downloads
- System configuration changes

**REQ-SEC-005**: The system shall implement session timeout after 8 hours of inactivity.

**REQ-SEC-006**: The system shall restrict file upload types and scan for malicious content.

### 5.3 Reliability Requirements

**REQ-REL-001**: The system shall maintain 99.5% uptime during business hours (8 AM - 6 PM, Monday-Friday).

**REQ-REL-002**: The system shall implement automated backup procedures with daily database backups and weekly full system backups.

**REQ-REL-003**: The system shall provide graceful error handling with user-friendly error messages.

**REQ-REL-004**: The system shall implement database transaction integrity to prevent data corruption.

### 5.4 Availability Requirements

**REQ-AVAIL-001**: The system shall be available 24/7 except for scheduled maintenance windows.

**REQ-AVAIL-002**: Scheduled maintenance shall be limited to weekends and announced 48 hours in advance.

**REQ-AVAIL-003**: The system shall implement automatic failover mechanisms for critical components.

### 5.5 Maintainability Requirements

**REQ-MAINT-001**: The system shall use modular architecture allowing independent updates of components.

**REQ-MAINT-002**: The system shall provide comprehensive logging for troubleshooting and debugging.

**REQ-MAINT-003**: The system shall include automated testing capabilities for regression testing.

**REQ-MAINT-004**: The system shall be documented with inline code comments and external technical documentation.

### 5.6 Portability Requirements

**REQ-PORT-001**: The system shall be deployable on multiple Linux distributions (Ubuntu, CentOS, RHEL).

**REQ-PORT-002**: The system shall use standard web technologies ensuring browser compatibility.

**REQ-PORT-003**: The system shall support database migration between MySQL and MariaDB.

### 5.7 Scalability Requirements

**REQ-SCALE-001**: The system architecture shall support horizontal scaling by adding additional web servers.

**REQ-SCALE-002**: The system shall handle increasing document storage requirements up to 100GB initially.

**REQ-SCALE-003**: The system shall support user base growth from 500 to 2000 users without architectural changes.

### 5.8 Usability Requirements

**REQ-USAB-001**: New users shall be able to complete basic tasks (login, view documents, read announcements) within 15 minutes of first access.

**REQ-USAB-002**: The system shall provide contextual help and tooltips for complex functions.

**REQ-USAB-003**: The system shall maintain consistent user interface elements across all pages.

**REQ-USAB-004**: The system shall provide keyboard navigation support for accessibility.

---

## 6. Other Requirements

### 6.1 Legal Requirements

**REQ-LEGAL-001**: The system shall comply with Tanzania Data Protection Act requirements for personal data handling.

**REQ-LEGAL-002**: The system shall provide data export capabilities for compliance with data portability requirements.

**REQ-LEGAL-003**: The system shall implement data retention policies allowing automatic deletion of expired content.

### 6.2 Regulatory Requirements

**REQ-REG-001**: The system shall maintain audit trails for compliance with government transparency requirements.

**REQ-REG-002**: The system shall support data backup and recovery procedures meeting NIMR's disaster recovery policies.

### 6.3 Cultural and Localization Requirements

**REQ-CULT-001**: The system shall support English language interface with consideration for Swahili language support in future versions.

**REQ-CULT-002**: The system shall use Tanzania-appropriate date and time formats.

**REQ-CULT-003**: The system shall accommodate NIMR's organizational culture and hierarchy in user interface design.

### 6.4 Training Requirements

**REQ-TRAIN-001**: The system shall include built-in user guidance and tutorials.

**REQ-TRAIN-002**: The system shall provide administrator training documentation covering all administrative functions.

**REQ-TRAIN-003**: The system shall include video tutorials for common user tasks.

---

## 7. Appendices

### Appendix A: Glossary

**Administrator**: User with elevated privileges to manage content and users within their department or system-wide.

**Announcement**: System-wide or targeted communication broadcast to users through the platform.

**Centre**: Regional NIMR research facilities located outside headquarters.

**Dashboard**: Main system interface providing overview and quick access to key functions.

**Department**: Functional organizational units within NIMR (e.g., HR, IT, Finance).

**Document Management**: Core system functionality for uploading, organizing, and accessing digital documents.

**Headquarters**: Main NIMR facility housing central administration and multiple departments.

**Role-Based Access Control (RBAC)**: Security model restricting system access based on user roles and responsibilities.

**Super Administrator**: User with highest system privileges including user management and system configuration.

### Appendix B: Acronyms

- **API**: Application Programming Interface
- **CSRF**: Cross-Site Request Forgery
- **CSV**: Comma-Separated Values
- **HTTPS**: Hypertext Transfer Protocol Secure
- **NIMR**: National Institute for Medical Research
- **PDF**: Portable Document Format
- **RBAC**: Role-Based Access Control
- **REST**: Representational State Transfer
- **SMTP**: Simple Mail Transfer Protocol
- **SQL**: Structured Query Language
- **SRS**: Software Requirements Specification
- **SSL**: Secure Sockets Layer
- **TLS**: Transport Layer Security
- **UI**: User Interface
- **URL**: Uniform Resource Locator
- **WCAG**: Web Content Accessibility Guidelines
- **XSS**: Cross-Site Scripting

### Appendix C: Revision History

| Version | Date | Description | Author |
|---------|------|-------------|---------|
| 1.0.0 | August 2025 | Initial SRS creation | NIMR IT Team |
| 2.0.0 | September 8, 2025 | Updated for enhanced document management features | NIMR IT Team |

---

**Document Control:**
- **Classification**: Internal Use
- **Distribution**: NIMR IT Department, Project Stakeholders
- **Review Cycle**: Quarterly
- **Next Review Date**: December 8, 2025

**Approval:**
- **Technical Lead**: [Name, Date]
- **Project Manager**: [Name, Date]
- **NIMR IT Director**: [Name, Date]
