<?php

return [
    'title_home'     => 'Dashboard',
    'message_home'   => 'You are logged in!',

    // Nova Resource Labels
    'resource_label_plural_lookup_address_types'            => 'Lookup Address Types',
    'resource_label_singular_lookup_address_types'          => 'Lookup Address Type',
    'resource_label_plural_lookup_domains'                  => 'Lookup Domains',
    'resource_label_singular_lookup_domains'                => 'Lookup Domain',
    'resource_label_plural_lookup_email_types'              => 'Lookup Email Types',
    'resource_label_singular_lookup_email_types'            => 'Lookup Email Type',
    'resource_label_plural_lookup_lasallesoftware_events'   => 'Lookup LaSalle Software Events',
    'resource_label_singular_lookup_lasallesoftware_events' => 'Lookup LaSalle Software Event',
    'resource_label_plural_lookup_roles'                    => 'Lookup User Roles',
    'resource_label_singular_lookup_roles'                  => 'Lookup User Role',
    'resource_label_plural_lookup_social_types'             => 'Lookup Social Types',
    'resource_label_singular_lookup_social_types'           => 'Lookup Social Type',
    'resource_label_plural_lookup_telephone_types'          => 'Lookup Telephone Types',
    'resource_label_singular_lookup_telephone_types'        => 'Lookup Telephone Type',
    'resource_label_plural_lookup_website_types'            => 'Lookup Website Types',
    'resource_label_singular_lookup_website_types'          => 'Lookup Website Type',

    'resource_label_plural_addresses'                       => 'Addresses',
    'resource_label_singular_addresses'                     => 'Address',
    'resource_label_plural_emails'                          => 'Email Addresses',
    'resource_label_singular_emails'                        => 'Email Address',
    'resource_label_plural_social_sites'                    => 'Social Sites',
    'resource_label_singular_social_sites'                  => 'Social Site',
    'resource_label_plural_telephone_numbers'               => 'Telephone Numbers',
    'resource_label_singular_telephone_numbers'             => 'Telephone Number',
    'resource_label_plural_websites'                        => 'Websites',
    'resource_label_singular_websites'                      => 'Website',

    'resource_label_plural_companies'                       => 'Companies',
    'resource_label_singular_companies'                     => 'Company',
    'resource_label_plural_persons'                         => 'People',
    'resource_label_singular_persons'                       => 'Person',

    // Nova Panels Labels
    'panel_system_fields' => 'System Fields',

    // Nova Field Labels
    'field_name_created_at'         => 'created at',
    'field_name_created_by'         => 'created by',
    'field_name_updated_at'         => 'updated at',
    'field_name_updated_by'         => 'updated by',
    'field_name_lookup_title'       => 'Title',
    'field_name_lookup_description' => 'Description',
    'field_name_lookup_enabled'     => 'Enabled',

    'field_name_address'            => 'Address',
    'field_name_email'              => 'Email Address',
    'field_name_social'             => 'Social Site',
    'field_name_telephone'          => 'Telephone Number',
    'field_name_website'            => 'Website',

    'field_name_anniversary'        => 'Anniversary',
    'field_name_birthday'           => 'Birthday',
    'field_name_deceased'           => 'Deceased',
    'field_name_comments_date'      => 'Description for the Dates',
    'field_name_comments'           => 'Comments',
    'field_name_featured_image'     => 'Featured Image',
    'field_name_first_name'         => 'First Name',
    'field_name_position'           => 'Position',
    'field_name_profile'            => 'Profile',
    'field_name_middle_name'        => 'Middle Name',
    'field_name_salutation'         => 'Salutation',
    'field_name_surname'            => 'Surname',

    'field_name_name'               => 'Name',
    'field_name_addressmaplink'     => 'Google Maps URL',

    'field_name_country_code'       => 'Country Code',
    'field_name_area_code'          => 'Area Code',
    'field_name_telephone_number'   => 'Telephone Number',
    'field_name_extension'          => 'Extension',

    // Nova Field Headings
    'field_heading_system_fields'   => 'Automated system fields:',
    'field_heading_address_type'    => 'An address is associated with a "type":',
    'field_heading_email_type'      => 'An email is associated with a "type":',
    'field_heading_social_type'     => 'A social site is associated with a "type":',
    'field_heading_telephone_type'  => 'A telephone number is associated with a "type":',
    'field_heading_website_type'    => 'A website is associated with a "type":',

    'field_heading_addresses_address'      => 'Address',
    'field_heading_persons_name'           => 'Name:',
    'field_heading_addresses_general_info' => 'General Information',
    'field_heading_persons_dates'          => 'Dates:',
    'field_heading_persons_general_info'   => 'General Information:',

    // Nova Field Help
    'field_help_brief'              => 'please keep it as brief as possible',
    'field_help_lookup_name'        => 'the name of this lookup record',
    'field_help_max_255_chars'      => 'maximum of 255 characters',
    'field_help_optional'           => 'optional',
    'field_help_required'           => 'required',
    'field_help_salutation'         => 'Mr., Mrs., Ms., Dr., etc',
    'field_help_unique'             => 'must be unique',
    'field_help_url'                => 'must be a url (include the  "http" part)"',
    'field_help_country_code_website_reference' => 'see <a href="https://countrycode.org" target="_blank">CountryCode.org</a> for country codes',

    // Exceptions
    'exception_message_date_cast'   => "Date field must cast to 'date' in Eloquent model.",

    // Rules
    'rules_addresses_unique_message'  => 'This address already exists',
    'rules_persons_unique_message'    => 'This person already exists',
    'rules_telephones_unique_message' => 'This telephone number already exists',

    // Miscellaneous
    'not_specified'                 => 'not specified',

];
