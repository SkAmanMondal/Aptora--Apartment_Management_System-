apartment-system/
│
├── index.html              # Landing page
├── login.php               # Common login (Admin / Owner)
├── logout.php              # Logout & destroy session
├── db.php                  # Database connection
│
├── css/
│   ├── common.css          # Global styles (body, buttons, links)
│   ├── auth.css            # Login page styles
│   ├── dashboard.css       # Sidebar, cards, layout
│   ├── forms.css           # All forms (add flat, add owner, pay)
│   └── tables.css          # Tables (lists, history)
│
├── includes/
│   ├── header.php          # Header + sidebar
│   ├── footer.php          # Footer
│   └── auth.php            # Session & role check
│
├── superadmin/
│   ├── dashboard.php       # Overview
│   ├── add_apartment.php   # Create apartment + admin
│   ├── edit_apartment.php
│   └── delete_apartment.php
│
├── admin/
│   ├── dashboard.php       # Apartment stats
│   ├── add_flat.php        # STEP 1: Create flat only
│   ├── manage_flats.php    # View / delete flats
│   ├── add_owner.php       # STEP 2: Create owner (user)
│   ├── manage_owner.php    # Edit / remove owner
│   └── payments.php        # View collections
│
├── owner/
│   ├── dashboard.php       # Owner overview
│   ├── pay_maintenance.php # Pay maintenance
│   ├── payment_history.php # View own payments
│   └── profile.php         # View flat & personal info
│
└── uploads/
    └── receipts/           # Payment receipts (future use)
