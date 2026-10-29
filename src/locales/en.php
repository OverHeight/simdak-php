<?php
/**
 * English Translation Dictionary
 */

return [
    // App
    'app_name'           => 'SIMDAK',
    'app_tagline'        => 'Employee Management Information System',
    
    // Auth - Login
    'login_title'        => 'Sign in to System',
    'login_desc'         => 'Manage employee records securely and centrally.',
    'username'           => 'Username',
    'password'           => 'Password',
    'login_btn'          => 'Sign In',
    'logout'             => 'Sign Out',
    'logged_in_as'       => 'Signed in as',
    'login_failed'       => 'Invalid username or password.',
    'login_required'     => 'Please sign in to access this page.',
    'logout_success'     => 'You have successfully logged out.',
    'csrf_invalid'       => 'Security token validation failed or session expired. Please try again.',

    // Auth - Register
    'register_title'          => 'Create an Account',
    'register_desc'           => 'Create an administrator account to access the system.',
    'register_btn'            => 'Register Account',
    'confirm_password'        => 'Confirm Password',
    'dont_have_account'       => "Don't have an account?",
    'already_have_account'    => 'Already have an account?',
    'register_here'           => 'Register here',
    'login_here'              => 'Sign in here',
    'passwords_do_not_match'  => 'Password confirmation does not match the password.',
    'username_already_taken'  => 'This username is already taken. Please choose another.',
    'username_min_length'     => 'Username must be at least 3 characters without spaces.',
    'password_min_length'     => 'Password must be at least 6 characters for security.',
    'register_success'        => 'Registration successful! Please sign in with your new account.',

    // Navigation & Actions
    'dashboard'          => 'Employee Directory',
    'add_employee'       => 'Add Employee',
    'edit_employee'      => 'Edit Employee Details',
    'delete_employee'    => 'Delete Employee',
    'confirm_delete'     => 'Are you sure you want to delete this employee?',
    'save'               => 'Save Record',
    'update'             => 'Update Record',
    'cancel'             => 'Cancel',
    'back'               => 'Back to Directory',
    'actions'            => 'Actions',
    'search'             => 'Search by name, email, or position...',
    'search_shortcut'    => 'Press / to search',
    'filter_department'  => 'All Departments',
    'filter_status'      => 'All Statuses',
    'reset_filter'       => 'Reset Filters',
    'showing'            => 'Showing',
    'of'                 => 'of',
    'employees_count'    => 'employees',
    'no_data_found'      => 'No employee records matching your query.',

    // Form & Table Fields
    'id'                 => 'ID',
    'name'               => 'Full Name',
    'email'              => 'Email Address',
    'position'           => 'Position',
    'department'         => 'Department',
    'status'             => 'Employment Status',
    'employment_type'    => 'Employment Type',
    'created_at'         => 'Date Added',
    'updated_at'         => 'Last Updated',

    // Status Values
    'status_active'      => 'Active',
    'status_probation'   => 'Probation',
    'status_inactive'    => 'Inactive',

    // Employment Type Values
    'type_full_time'     => 'Full-Time',
    'type_contract'      => 'Contract',
    'type_internship'    => 'Internship',

    // Messages & Validation
    'all_fields_required'=> 'All fields are required.',
    'invalid_email'      => 'The email address format is invalid.',
    'email_already_used' => 'This email address is already registered to another employee.',
    'employee_created'   => 'New employee record created successfully.',
    'employee_updated'   => 'Employee record updated successfully.',
    'employee_deleted'   => 'Employee record deleted successfully.',
    'employee_not_found' => 'Employee record not found.',
    'invalid_id'         => 'Invalid employee ID.',
    'field_too_long'     => 'Input exceeds maximum allowed length (max. 100 characters).',

    // Language
    'language'           => 'Language',
    'lang_id'            => 'Bahasa Indonesia',
    'lang_en'            => 'English',
];
