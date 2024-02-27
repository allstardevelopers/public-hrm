
# HRM Attendance System (All star Technologies)

HRM is an Employee Management System designed to efficiently track the working hours, breaks, and various activities of employees. This system provides a comprehensive solution for managing employee data and organizational events.

## Table of Contents
    - [Features](#Features)
    - [Technologies Used](#Technologies)
    - [Configurations Files](#Configurations)
    - [Cron Jobs](#Cron Jobs)

## Features
   1. **Attendance Tracking:**
    - Records daily, weekly, and monthly working hours of employees.
    - sMonitors breaks taken by employees during working hours.
   2. **Employee Leave:**
    - Auto recors Employee Yearly Leave Balance.
    - Full Day Leave (Employee Request).
    - Half Day Leave (Employee Request).
    - Absent  Leave (Auto Marked Absent Employee).
   

## Technologies Used
    - Web Application (HRM)
        -- PHP 8.1
        -- Laravel Framework 8.73.2
        -- MySQL Database
        -- Google Firebase for push notifications
        -- Backend End
            Authentication Laravel Ui, Sanctum, 
        -- Front End
          -- Blade Template
          -- Bootstrap v4.3.1
          -- Bootstrap v4.3.1
          -- jQuery v3.3.1 
    - Desktop Applictaion (Clock App)
        -- Python 3.7
        -- Tkinter (UI)
        -- Request Apis (HRM)


## Configurations Files
- Database Configurations
   - localhost
    - DB_CONNECTION=mysql
    - DB_HOST=localhost
    - DB_PORT=3306
    - DB_DATABASE=employee_db
    - DB_USERNAME=root
    - DB_PASSWORD=

## Cron Jobs
  -  Employee Tracking  (Every 5 minutes) 
    - Checking Tracked employee clock response if not then clockout them.
  -  Daily Schedule  (Every Day)
    - Marke Absent Employees
    -  Sent Notifications on probation periods
## Accounts 
    - Admin Accounts 
    - Account 1 
        - HR 
        - hr@allstar-technologies.com
        - hr@ems.com 
    - Account 2 
        - Admin
        - admin@allstar-technologies.com
        - admin@ems.com

## URL 
  - http://hrm.allstartechnologies.co.uk/



