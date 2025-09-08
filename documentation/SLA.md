# Service Level Agreement (SLA)
## NIMR Intranet System

**Document Version**: 2.0.0  
**Date**: September 8, 2025  
**Effective Date**: October 1, 2025  
**Review Date**: October 1, 2026  
**Client**: National Institute for Medical Research (NIMR)  
**Service Provider**: NIMR IT Department  

---

## Table of Contents

1. [Executive Summary](#1-executive-summary)
2. [Service Description](#2-service-description)
3. [Service Level Objectives](#3-service-level-objectives)
4. [Performance Metrics](#4-performance-metrics)
5. [Availability and Uptime](#5-availability-and-uptime)
6. [Support Services](#6-support-services)
7. [Security and Compliance](#7-security-and-compliance)
8. [Incident Management](#8-incident-management)
9. [Change Management](#9-change-management)
10. [Service Credits and Penalties](#10-service-credits-and-penalties)
11. [Roles and Responsibilities](#11-roles-and-responsibilities)
12. [Review and Reporting](#12-review-and-reporting)

---

## 1. Executive Summary

### 1.1 Purpose
This Service Level Agreement (SLA) defines the expected service levels, performance standards, and operational commitments for the NIMR Intranet System. It establishes measurable targets and responsibilities between the NIMR IT Department (Service Provider) and NIMR staff (Service Users).

### 1.2 Scope
This SLA covers all aspects of the NIMR Intranet System including:
- Web application availability and performance
- Document management services
- User support and maintenance
- Security and data protection
- System monitoring and reporting

### 1.3 SLA Period
- **Effective Date**: October 1, 2025
- **Review Period**: Annual
- **Next Review**: October 1, 2026

---

## 2. Service Description

### 2.1 NIMR Intranet System Overview
The NIMR Intranet System is a web-based platform providing:

**Core Services:**
- Document management and storage
- Organizational announcements and communications
- User management and authentication
- Department-wise content organization
- Search and discovery capabilities

**Technical Infrastructure:**
- Web server hosting and maintenance
- Database management and backup
- File storage and security
- System monitoring and logging
- Regular updates and patches

### 2.2 Service Components

| Component | Description | Service Level |
|-----------|-------------|---------------|
| Web Application | Main intranet interface | 99.5% uptime |
| Document Storage | File upload/download system | 99.7% availability |
| Database Services | Data storage and retrieval | 99.8% availability |
| User Authentication | Login and security system | 99.9% availability |
| Email Notifications | System-generated emails | 95% delivery rate |

---

## 3. Service Level Objectives

### 3.1 System Availability

**Primary Objective: 99.5% Uptime**
- **Measurement Period**: Monthly
- **Calculation**: (Total Minutes - Downtime Minutes) / Total Minutes × 100
- **Exclusions**: Planned maintenance windows
- **Target**: 99.5% availability during business hours (8 AM - 6 PM, Monday-Friday)

**Extended Hours Objective: 99.0% Uptime**
- **Coverage**: Outside business hours and weekends
- **Target**: 99.0% availability during non-business hours

### 3.2 Performance Standards

**Response Time Objectives:**

| Service Function | Target Response Time | Peak Load Target |
|------------------|---------------------|------------------|
| Page Load (Homepage) | ≤ 3 seconds | ≤ 5 seconds |
| Document Search | ≤ 2 seconds | ≤ 4 seconds |
| File Upload (≤10MB) | ≤ 30 seconds | ≤ 45 seconds |
| File Download | ≤ 5 seconds | ≤ 10 seconds |
| User Authentication | ≤ 2 seconds | ≤ 3 seconds |

**Throughput Objectives:**
- **Concurrent Users**: Support 200+ simultaneous users
- **Peak Load**: Support 300+ users during peak periods (9-11 AM, 2-4 PM)
- **File Upload Capacity**: 50MB maximum file size
- **Daily Upload Volume**: Support 1GB+ daily upload capacity

### 3.3 Scalability Targets

**User Growth Support:**
- Current capacity: 500 active users
- Growth target: Scale to 1,000 users within 12 months
- Performance degradation: <10% at 150% of target capacity

**Storage Scalability:**
- Current capacity: 100GB document storage
- Growth target: Scale to 500GB within 24 months
- Backup retention: 30 days for deleted documents

---

## 4. Performance Metrics

### 4.1 Key Performance Indicators (KPIs)

**Availability Metrics:**
- System uptime percentage
- Mean Time Between Failures (MTBF)
- Mean Time To Recovery (MTTR)
- Planned vs. unplanned downtime ratio

**Performance Metrics:**
- Average page response time
- 95th percentile response time
- Transaction success rate
- Error rate percentage

**User Experience Metrics:**
- User login success rate
- Document upload success rate
- Search query success rate
- Mobile accessibility compliance

### 4.2 Measurement Methods

**Automated Monitoring:**
- 24/7 system monitoring with 5-minute intervals
- Real-time performance dashboards
- Automated alerting for threshold breaches
- Log aggregation and analysis

**Manual Testing:**
- Weekly performance testing
- Monthly user experience audits
- Quarterly load testing
- Annual security assessments

### 4.3 Reporting Schedule

| Report Type | Frequency | Recipients | Delivery Method |
|-------------|-----------|------------|-----------------|
| System Status | Real-time | IT Team | Dashboard |
| Performance Summary | Weekly | IT Management | Email |
| Monthly SLA Report | Monthly | NIMR Management | Email + Meeting |
| Quarterly Review | Quarterly | All Stakeholders | Formal Presentation |
| Annual Assessment | Annually | Executive Leadership | Comprehensive Report |

---

## 5. Availability and Uptime

### 5.1 Uptime Commitments

**Business Hours (8 AM - 6 PM, Monday-Friday):**
- Target Availability: 99.5%
- Maximum Acceptable Downtime: 10.8 hours per month
- Maximum Single Incident Duration: 4 hours

**Extended Hours (Evenings, Weekends, Holidays):**
- Target Availability: 99.0%
- Maximum Acceptable Downtime: 7.2 hours per month
- Emergency maintenance allowed with 24-hour notice

### 5.2 Planned Maintenance

**Maintenance Windows:**
- **Frequency**: Monthly
- **Duration**: Maximum 4 hours per month
- **Schedule**: Second Sunday of each month, 10 PM - 2 AM
- **Advance Notice**: Minimum 72 hours

**Emergency Maintenance:**
- **Criteria**: Critical security patches, system failures
- **Approval**: IT Director approval required
- **Notice**: Minimum 4 hours when possible
- **Duration**: Maximum 2 hours during business hours

### 5.3 Uptime Measurement

**Exclusions from Downtime Calculations:**
- Scheduled maintenance within approved windows
- Third-party service failures beyond NIMR control
- Natural disasters and force majeure events
- User-induced errors or unauthorized access attempts

**Monitoring Points:**
- External monitoring from multiple geographic locations
- Internal network monitoring
- Application layer health checks
- Database connectivity monitoring

---

## 6. Support Services

### 6.1 Support Tiers

**Tier 1 - Help Desk Support:**
- **Coverage**: Business hours (8 AM - 5 PM, Monday-Friday)
- **Response**: Password resets, basic navigation help
- **Staff**: NIMR Help Desk
- **Contact**: helpdesk@nimr.or.tz | Extension 150

**Tier 2 - Technical Support:**
- **Coverage**: Business hours plus on-call for emergencies
- **Response**: Technical issues, functionality problems
- **Staff**: IT Support Specialists
- **Contact**: itsupport@nimr.or.tz | Extension 151

**Tier 3 - System Administration:**
- **Coverage**: 24/7 for critical issues
- **Response**: System failures, security incidents
- **Staff**: Senior System Administrators
- **Contact**: sysadmin@nimr.or.tz | Emergency: +255-xxx-xxx-xxx

### 6.2 Response Time Commitments

| Priority Level | Definition | Response Time | Resolution Target |
|----------------|------------|---------------|-------------------|
| **Critical** | System completely unavailable | 30 minutes | 4 hours |
| **High** | Significant functionality impaired | 2 hours | 8 hours |
| **Medium** | Minor functionality issues | 4 hours | 24 hours |
| **Low** | Enhancement requests, questions | 8 hours | 72 hours |

### 6.3 Support Channels

**Primary Channels:**
- **Email**: support@nimr.or.tz (Preferred for non-urgent issues)
- **Phone**: +255-xxx-xxx-xxx (Business hours)
- **Internal Portal**: IT Service Desk system
- **Emergency Hotline**: +255-xxx-xxx-xxx (24/7 for critical issues)

**Self-Service Options:**
- Online user documentation and guides
- Video tutorials for common tasks
- FAQ section within the intranet
- System status page for real-time updates

---

## 7. Security and Compliance

### 7.1 Security Standards

**Data Protection:**
- Encryption in transit (TLS 1.2+)
- Encryption at rest for sensitive data
- Regular security vulnerability assessments
- Compliance with Tanzania Data Protection Act

**Access Control:**
- Role-based access control (RBAC)
- Multi-factor authentication for administrators
- Regular access reviews and user deprovisioning
- Password policy enforcement

**Monitoring and Auditing:**
- 24/7 security monitoring
- Comprehensive audit logging
- Quarterly security assessments
- Annual penetration testing

### 7.2 Backup and Recovery

**Backup Schedule:**
- **Database**: Daily automated backups at 2 AM
- **Documents**: Daily incremental, weekly full backup
- **System Configuration**: Weekly backup
- **Retention**: 30 days online, 12 months archived

**Recovery Objectives:**
- **Recovery Time Objective (RTO)**: 4 hours for critical systems
- **Recovery Point Objective (RPO)**: 24 hours maximum data loss
- **Backup Testing**: Monthly restoration tests
- **Disaster Recovery**: Full plan tested annually

### 7.3 Compliance Requirements

**Regulatory Compliance:**
- Tanzania Government IT Standards
- Data Protection and Privacy regulations
- NIMR internal security policies
- International best practices (ISO 27001 guidelines)

**Audit Requirements:**
- Annual internal security audit
- Quarterly access reviews
- Monthly backup verification
- Continuous compliance monitoring

---

## 8. Incident Management

### 8.1 Incident Classification

**Priority 1 - Critical:**
- Complete system outage
- Security breach or data loss
- Login system failure affecting all users
- **Response**: 30 minutes, 24/7 coverage

**Priority 2 - High:**
- Significant functionality unavailable
- Performance degradation affecting >50% of users
- Document upload/download system failure
- **Response**: 2 hours during business hours

**Priority 3 - Medium:**
- Minor functionality issues
- Performance issues affecting <25% of users
- Non-critical feature failures
- **Response**: 4 hours during business hours

**Priority 4 - Low:**
- Cosmetic issues, documentation updates
- Enhancement requests
- Training and how-to questions
- **Response**: 8 hours during business hours

### 8.2 Incident Response Process

**Incident Detection:**
1. Automated monitoring alerts
2. User-reported issues via support channels
3. Proactive system health checks
4. Third-party service notifications

**Response Workflow:**
1. **Detection** (0-15 minutes): Incident identified and logged
2. **Assessment** (15-30 minutes): Priority assigned, team notified
3. **Response** (30 minutes-4 hours): Active resolution efforts
4. **Resolution**: Issue resolved and systems restored
5. **Post-Incident**: Root cause analysis and documentation

### 8.3 Communication During Incidents

**Internal Communication:**
- Immediate notification to IT management for P1/P2 incidents
- Hourly status updates during active incidents
- Post-incident report within 48 hours

**User Communication:**
- System status page updates within 30 minutes
- Email notifications for incidents affecting >100 users
- Regular updates every 2 hours for extended outages
- Resolution notification to all affected users

---

## 9. Change Management

### 9.1 Change Categories

**Emergency Changes:**
- Critical security patches
- System failure repairs
- **Approval**: IT Director or designee
- **Timeline**: Immediate implementation allowed

**Standard Changes:**
- Regular updates and patches
- New feature deployments
- **Approval**: Change Advisory Board
- **Timeline**: Minimum 72-hour notice

**Major Changes:**
- System upgrades or migrations
- Architecture modifications
- **Approval**: Executive leadership
- **Timeline**: Minimum 2-week notice and planning

### 9.2 Change Process

**Planning Phase:**
1. Change request submission and review
2. Impact assessment and risk analysis
3. Testing in development environment
4. Approval from appropriate authority

**Implementation Phase:**
1. User notification of upcoming changes
2. Backup verification before changes
3. Implementation during approved window
4. Post-change verification and testing

**Validation Phase:**
1. System functionality verification
2. Performance monitoring for 24 hours
3. User feedback collection
4. Rollback execution if issues arise

### 9.3 Change Communication

**Advance Notifications:**
- Email notifications to all users
- System banner announcements
- Department liaison communications
- Updated documentation and training materials

**Change Documentation:**
- Detailed change logs maintained
- Version tracking for all system components
- Rollback procedures documented
- Lessons learned captured and shared

---

## 10. Service Credits and Penalties

### 10.1 Service Level Credits

**Availability Credits:**
- **99.0% - 99.4% uptime**: 5% of monthly IT budget to user training
- **98.0% - 98.9% uptime**: 10% of monthly IT budget to system improvements
- **<98.0% uptime**: 15% of monthly IT budget to infrastructure upgrades

**Performance Credits:**
- **Response time > 150% of target**: Additional monitoring tools purchase
- **Multiple performance breaches**: Performance improvement project initiation

### 10.2 Service Improvement Fund

**Funding Source:**
- Redirected budget from service credits
- Additional IT improvement budget allocations
- Priority given to areas with service level misses

**Investment Priorities:**
1. Infrastructure upgrades for reliability
2. Performance optimization initiatives
3. Additional staff training and certification
4. Enhanced monitoring and management tools

### 10.3 Escalation for Repeated Failures

**Escalation Triggers:**
- Three consecutive months below service level targets
- Critical incident frequency exceeding acceptable limits
- User satisfaction scores below 80%

**Escalation Actions:**
1. Formal review with NIMR executive leadership
2. External consultant assessment if required
3. Service improvement plan development
4. Additional resource allocation consideration

---

## 11. Roles and Responsibilities

### 11.1 NIMR IT Department (Service Provider)

**IT Director:**
- Overall service accountability and strategic oversight
- SLA compliance monitoring and reporting
- Resource allocation and staff management
- Executive stakeholder communication

**System Administrators:**
- Daily system operations and monitoring
- Incident response and resolution
- Change implementation and testing
- Security management and compliance

**Help Desk Staff:**
- User support and issue triage
- Documentation maintenance
- User training and communication
- First-level incident response

**Network Operations:**
- Infrastructure monitoring and maintenance
- Capacity planning and performance optimization
- Backup operations and disaster recovery
- Third-party vendor coordination

### 11.2 NIMR Management (Service Recipients)

**Executive Leadership:**
- Service level expectation setting
- Resource approval for improvements
- Strategic direction and priorities
- Overall satisfaction assessment

**Department Heads:**
- User requirement gathering and communication
- Staff training coordination within departments
- Change impact assessment and approval
- Service utilization monitoring

**IT Liaison Representatives:**
- Departmental IT requirements communication
- User training and support coordination
- Change communication to staff
- Feedback collection and reporting

### 11.3 End Users (Service Consumers)

**All NIMR Staff:**
- Responsible use of system resources
- Timely reporting of issues and concerns
- Participation in training programs
- Compliance with security policies

**Power Users:**
- Advanced feature utilization and feedback
- Peer support and knowledge sharing
- Testing of new features and updates
- Documentation improvement suggestions

---

## 12. Review and Reporting

### 12.1 Regular Reporting

**Monthly Service Reports:**
- **Availability**: Uptime percentages and downtime analysis
- **Performance**: Response time metrics and trends
- **Incidents**: Summary of issues and resolutions
- **User Activity**: Usage statistics and trends
- **Capacity**: Resource utilization and growth projections

**Quarterly Business Reviews:**
- Service level achievement against targets
- User satisfaction survey results
- Capacity planning and infrastructure needs
- Service improvement initiatives progress
- Budget and resource utilization review

### 12.2 SLA Review Process

**Annual SLA Review:**
- **Timeline**: Every October (anniversary of effective date)
- **Participants**: IT leadership, NIMR management, user representatives
- **Scope**: Complete SLA evaluation and updates
- **Outcome**: Updated SLA document for following year

**Quarterly Mini-Reviews:**
- Service level target assessment
- Emerging requirement identification
- Performance trend analysis
- Minor adjustment recommendations

### 12.3 Continuous Improvement

**Improvement Initiatives:**
- Regular assessment of industry best practices
- Technology upgrade evaluations
- Process optimization opportunities
- User experience enhancement projects

**Innovation Programs:**
- Pilot testing of new technologies
- User suggestion implementation
- Automation and efficiency improvements
- Integration with other NIMR systems

**Benchmarking:**
- Industry standard comparisons
- Peer organization service level reviews
- Technology vendor capability assessments
- Cost-benefit analysis for improvements

---

## Appendices

### Appendix A: Contact Information

| Role | Name | Email | Phone | Escalation |
|------|------|-------|-------|------------|
| IT Director | [Name] | director@nimr.or.tz | +255-xxx-xxx-xxx | NIMR Deputy Director |
| System Admin Lead | [Name] | sysadmin@nimr.or.tz | +255-xxx-xxx-xxx | IT Director |
| Help Desk Manager | [Name] | helpdesk@nimr.or.tz | +255-xxx-xxx-xxx | System Admin Lead |
| Security Officer | [Name] | security@nimr.or.tz | +255-xxx-xxx-xxx | IT Director |

### Appendix B: Service Hours

| Service Type | Business Hours | Extended Hours | Emergency Coverage |
|--------------|----------------|----------------|-------------------|
| Help Desk | Mon-Fri 8AM-5PM | Sat 9AM-1PM | On-call weekends |
| Technical Support | Mon-Fri 7AM-6PM | Sat-Sun 9AM-5PM | 24/7 for P1 issues |
| System Admin | Mon-Fri 24 hours | Weekend on-call | 24/7 emergency |
| Network Operations | 24/7 monitoring | 24/7 monitoring | 24/7 response |

### Appendix C: Escalation Matrix

| Issue Type | Level 1 | Level 2 | Level 3 | Level 4 |
|------------|---------|---------|---------|---------|
| Technical Issues | Help Desk | Tech Support | System Admin | IT Director |
| Security Incidents | Security Officer | IT Director | NIMR Management | External Authorities |
| Service Failures | System Admin | IT Director | NIMR Deputy Director | Director General |
| User Complaints | Help Desk | Help Desk Manager | IT Director | NIMR Management |

---

**Document Approval:**

| Role | Name | Signature | Date |
|------|------|-----------|------|
| IT Director | [Name] | _________________ | _______ |
| NIMR Deputy Director | [Name] | _________________ | _______ |
| Director General | [Name] | _________________ | _______ |

**Document Control:**
- **Classification**: Official NIMR Policy Document
- **Distribution**: All NIMR Staff, IT Department, Management
- **Effective Date**: October 1, 2025
- **Review Cycle**: Annual
- **Next Review**: October 1, 2026
- **Version**: 2.0.0
