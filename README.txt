

Database Name: osaknows_database


//NO SQL Table Schema for Login, just fetch from table (register_students or register_admin/staff)\\



//SQL Table Schema for Registration\\
As student/faculty

CREATE TABLE register_students (
    user_id INT(7) AUTO_INCREMENT PRIMARY KEY,
    u_fullname VARCHAR(255) NOT NULL,
    u_course VARCHAR(255) NOT NULL,
    u_year INT(1) NOT NULL,
    id_number VARCHAR(50) NOT NULL UNIQUE,
    adzu_email VARCHAR(255) NOT NULL UNIQUE,
    u_password VARCHAR(255) NOT NULL,
    id_picture VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);


As admin

CREATE TABLE register_adminstaff (
    admin_id INT(11) AUTO_INCREMENT PRIMARY KEY,
    a_fullname VARCHAR(255) NOT NULL,
    a_role VARCHAR(50) NOT NULL,
    a_idnumber VARCHAR(50) NOT NULL UNIQUE,
    a_adzuemail VARCHAR(255) NOT NULL UNIQUE,
    a_password VARCHAR(255) NOT NULL,
    a_idpicture VARCHAR(255) NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);





//SQL Table Schema for FAQS\\

CREATE TABLE faqs (
    faq_id INT AUTO_INCREMENT PRIMARY KEY,
    f_question TEXT NOT NULL,
    f_answer TEXT NOT NULL,
    f_status ENUM('active', 'removed') NOT NULL DEFAULT 'active',
    f_created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    f_updated_at TIMESTAMP NULL DEFAULT NULL,
    f_removed_at TIMESTAMP NULL DEFAULT NULL,
    f_order_index INT NULL DEFAULT 0,
    f_created_by INT NOT NULL,
    f_updated_by INT DEFAULT NULL,
    f_removed_by INT DEFAULT NULL,
    FOREIGN KEY (f_created_by) REFERENCES register_adminstaff(admin_id)
        ON DELETE RESTRICT ON UPDATE CASCADE,
    FOREIGN KEY (f_updated_by) REFERENCES register_adminstaff(admin_id)
        ON DELETE SET NULL ON UPDATE CASCADE,
    FOREIGN KEY (f_removed_by) REFERENCES register_adminstaff(admin_id)
        ON DELETE SET NULL ON UPDATE CASCADE
);




//SQL Table Schema for Claim Request\\

CREATE TABLE IF NOT EXISTS `claim_requests` (
  `C_ID` int(11) NOT NULL AUTO_INCREMENT,
  `C_ItemName` varchar(100) NOT NULL,
  `C_ItemPhoto` varchar(255) DEFAULT NULL,
  `C_NameOfClaimant` varchar(100) NOT NULL,
  `C_EmailOfClaimant` varchar(100) NOT NULL,
  `C_IDNumber` varchar(50) NOT NULL,
  `C_VerificationQ1` text DEFAULT NULL,
  `C_VerificationQ2` text NOT NULL,
  `C_DateLost` datetime NOT NULL,
  `C_SubmissionDate` datetime NOT NULL DEFAULT current_timestamp(),
  `C_Status` enum('pending','approved','rejected') DEFAULT 'pending',
  `approved_by_name` varchar(100) DEFAULT NULL,
  `rejected_by_name` varchar(100) DEFAULT NULL,
  `C_approved_at` datetime DEFAULT NULL,
  `C_rejected_at` datetime DEFAULT NULL,
  PRIMARY KEY (`C_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

ALTER TABLE `claim_requests`
ADD COLUMN `C_SubmittedBy` INT NOT NULL AFTER `C_SubmissionDate`,
ADD CONSTRAINT `fk_claim_submittedby_student`
  FOREIGN KEY (`C_SubmittedBy`) REFERENCES `register_students`(`user_id`)
  ON DELETE CASCADE;

ALTER TABLE claim_requests
MODIFY COLUMN C_SubmittedBy INT NOT NULL;




//ON DELETE CASCADE is for handling deleted accounts, such as if an account does not exist anymore, it deletes traces of that account to maintain records integrity and prevent orphaned data




//SQL Table Schema for Claimed Items\\
CREATE TABLE IF NOT EXISTS `claimed` (
  `c_id` int(11) NOT NULL AUTO_INCREMENT,
  `c_itemName` varchar(255) NOT NULL,
  `c_description` text DEFAULT NULL,
  `c_location` varchar(255) DEFAULT NULL,
  `claimant_name` varchar(255) DEFAULT NULL,
  `c_date` date NOT NULL,
  `c_time` time NOT NULL,
  `c_status` varchar(50) DEFAULT 'Claimed',
  `c_image` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`c_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

ALTER TABLE claimed
ADD COLUMN claimant_id INT NOT NULL AFTER claimant_name,
ADD CONSTRAINT fk_claimant_id
FOREIGN KEY (claimant_id) REFERENCES register_students(user_id)
ON DELETE CASCADE;




//SQL Table Schema for Found\\
CREATE TABLE IF NOT EXISTS `found` (
  `f_id` int(11) NOT NULL AUTO_INCREMENT,
  `f_itemName` varchar(255) NOT NULL,
  `f_description` text NOT NULL,
  `f_date` date NOT NULL,
  `f_time` time NOT NULL,
  `f_status` varchar(50) NOT NULL DEFAULT 'Unclaimed',
  `f_image` varchar(255) NOT NULL,
  `f_location` varchar(255) NOT NULL DEFAULT 'No Location',
  `finders` varchar(255) NOT NULL DEFAULT 'No Finder',
  `date_published` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`f_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

ALTER TABLE `found`
ADD COLUMN `f_uploadedBy` INT(11) DEFAULT NULL,
ADD CONSTRAINT `fk_uploaded_by_admin`
  FOREIGN KEY (`f_uploadedBy`) REFERENCES `register_adminstaff`(`admin_id`)
  ON DELETE SET NULL
  ON UPDATE CASCADE;





//SQL Table Schema for Feedback Forms\\

CREATE TABLE feedback_forms (
    FeForm_id INT AUTO_INCREMENT PRIMARY KEY,
    Fe_user_idnumber VARCHAR(50) NOT NULL,
    Fe_Type ENUM('general', 'complaint', 'inquiry') NOT NULL,
    Fe_Message TEXT NOT NULL,
    Fe_Status ENUM('resolved', 'pending', 'archived', 'removed') NOT NULL DEFAULT 'pending',
    Fe_submitted_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    user_id INT NOT NULL,  -- Foreign key to user_id of register_students
    Fe_resolved_at TIMESTAMP NULL DEFAULT NULL,
    Fe_StatusUpdatedBy INT NULL,  -- Now an admin_id (FK)
    Fe_ReplyMessage TEXT NULL,
    Fe_RepliedBy INT NULL,        -- Now an admin_id (FK)
    admin_id INT(11) NULL,        -- Foreign key to admin_id of register_adminstaff
    FOREIGN KEY (user_id) REFERENCES register_students(user_id) ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (admin_id) REFERENCES register_adminstaff(admin_id) ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (Fe_RepliedBy) REFERENCES register_adminstaff(admin_id) ON DELETE SET NULL ON UPDATE CASCADE,
    FOREIGN KEY (Fe_StatusUpdatedBy) REFERENCES register_adminstaff(admin_id) ON DELETE SET NULL ON UPDATE CASCADE
);




//SQL Table Schema for Site Statistics\\

CREATE TABLE site_stats (
    id INT AUTO_INCREMENT PRIMARY KEY,
    visit_count INT NOT NULL DEFAULT 1,
    user_id INT NULL,
    visited_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES register_students(user_id) ON DELETE SET NULL
);



