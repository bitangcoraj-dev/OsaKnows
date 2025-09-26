<?php
session_start();

// Check required session variables
if (
    !isset($_SESSION['name']) ||
    !isset($_SESSION['idNumber']) ||
    !isset($_SESSION['email']) ||
    !isset($_SESSION['role']) ||
    ($_SESSION['role'] !== 'admin' && !isset($_SESSION['course']))
) {
    echo "Session expired or invalid.";
    header("Refresh: 10; Location: login.php");
    exit();
}

// Only track visits for non-admin users
if ($_SESSION['role'] !== 'admin') {
    if (!isset($_SESSION['counted_visit'])) {
        include('db.php');

        $user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;

        // Insert a new visit
        $stmt = $conn->prepare("INSERT INTO site_stats (visit_count, user_id) VALUES (1, ?)");
        if (!$stmt) {
            die("Prepare failed: " . $conn->error);
        }

        $stmt->bind_param("i", $user_id);

        if ($stmt->execute()) {
            echo "";
            $_SESSION['counted_visit'] = true;
        } else {
            echo "" . $stmt->error;
        }

        $stmt->close();
    } else {
        echo "";
    }
}


?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OsaKnows</title>
    <link rel ="stylesheet" href="index.css">
    <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css" />
    
</head>
<body>

<header>
        <a href="#"><img src="assets/adzu.seal.png" alt="ADZU Logo" class="logo"></a>
        <nav>
            <ul class="nav-links">
                <li><a href="#">Home</a></li>
                <li><a href="#services">Services</a></li>
                <li><a href="#announcements">Announcements</a></li>
                <li><a href="#about-us">About Us</a></li>
                <li><a href="#footer">Contacts</a></li>
                <li>
                    <div class="dropdown" id="profileDropdown">
                        <button class="dropdown-icon" onclick="toggleDropdown()"><img src="assets/blueicon.png" class="nav-icon"></button>
                        <div class="dropdown-menu">
                            <a href="#"><?php if (isset($_SESSION['name'])) echo $_SESSION['name']?></a>
                            <a href="#"><?php if (isset($_SESSION['course'])) echo $_SESSION['course']?></a>
                            <a href="#"><?php if (isset($_SESSION['idNumber'])) echo $_SESSION['idNumber']?></a>
                            <a href="#"><?php if (isset($_SESSION['email'])) echo $_SESSION['email']?></a>
                            <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
                            <a href="admin/admin_dashboard.php">Go To Dashboard</a>
                            <?php endif; ?>
                            <a href="logout.php">Log Out</a>
                        </div>
                    </div>
                </li>
            </ul>
        </nav>

        <script>
            // Select the dropdown and dropdown icon
            const dropdown = document.getElementById('profileDropdown');
            const dropdownIcon = document.querySelector('.dropdown-icon');

            // Toggle dropdown on icon click
            dropdownIcon.addEventListener('click', (e) => {
                e.stopPropagation(); // Prevent the click from propagating to the window
                dropdown.classList.toggle('open');
            });

            // Close dropdown when clicking outside
            window.addEventListener('click', (e) => {
                if (!dropdown.contains(e.target)) {
                    dropdown.classList.remove('open');
                }
            });
        </script>
    </header>

    <section class = "home">
        <div class = "slideshow-background">
        </div>

        <div class = "home-content">
            <h1> OSAKnows </h1>
            <a href="#"><img src="assets/osa.logo.png" alt="OSA Logo" class="logo2"></a>
            <p>The Office of Student Affairs (OSA) is responsible for the coordination, leadership development programs, and supervision of all academic and non-academic activities of students’ in-campus and off-campus.</p>
        </div>
    </section>

    <section class = "services" id = "services">
        <h2> Services </h2>
        <div class = "service-box">
            <div class = "box">
                <img src = "assets/UniversityHandbook.png" alt = "University Handbook" class = "box-img">
                <h3> University Handbook </h3>
                <a href = "assets/ADZU - COLLEGE - STUDENT HANDBOOK..pdf"> View College Handbook </a>
            </div>
            <div class = "box">
                <img src = "assets/LostandFoundIcon.png" alt = "Lost and Found" class = "box-img">
                <h3> Lost and Found Items </h3>
                <a href = "forms/Lost&FoundMain/Lost&Found&Claimed-Item-Page.php"> View Lost and Found Items </a>
            </div>
            <div class = "box">
                <img src = "assets/FrequentlyAskedQuestions.png" alt = "Frequently Asked Questions" class = "box-img">
                <h3> Frequently Asked Questions (FAQs) </h3>
                <a href = "pages/FAQSPAge/faqs.php"> View FAQs </a>
            </div>
        </div>
    </section>

    <section class = "about-us" id="about-us">
        <h1> Student Formators </h1>
        <div class = "grid-container">
            <div class="profile">
            <img src="assets/OSA_staff(1).png" alt="Ms Gianne Kathleen S Dela Cruz">
            <h3>Ms Gianne Kathleen S Dela Cruz</h3>
            <p>Director</p>
            </div>

            <div class="profile">
            <img src="assets/OSA_staff(2).png" alt="Ms Coleen L Doren-Tugusan">
            <h3>Ms Coleen L Doren-Tugusan</h3>
            <p>Office Support</p>
            </div>

            <div class="profile">
            <img src="assets/OSA_staff(3).png" alt="Mr Johnhartford B Ramos">
            <h3>Mr Johnhartford B Ramos</h3>
            <p>Office Support</p>
            </div>

            <div class="profile">
            <img src="assets/OSA_staff(4).png" alt="Mr Marc Zuriel R Rabanal">
            <h3>Mr Marc Zuriel R Rabanal</h3>
            <p>Office Support</p>
            </div>

        </div>
    </section>

    <!--Announcements section-->
    <section class="announcements" id="announcements">
    <h2>Announcements</h2>

    <!-- Swiper Container -->
    <div class="swiper mySwiper">
        <div class="swiper-wrapper">

            <!-- Slide 1 -->
            <div class="swiper-slide">
                <div class="announcement-box">
                    <h3>SNAPSHOTS | Convergence 2025 </h3>
                    <iframe 
                        title="SNAPSHOTS | Convergence 2025 Facebook Post"
                        loading="lazy"
                        src="https://www.facebook.com/plugins/post.php?href=https%3A%2F%2Fwww.facebook.com%2Fateneodezamboangauniversity%2Fposts%2Fpfbid02zCNgtY2UXx5JoNZYNuipC6JrjyigdXfKsySoVrWHqcWFnfdukZbzexLFp6zu3emTl&show_text=true&width=500" 
                        width="500" 
                        height="792" 
                        style="border:none;overflow:visible" 
                        scrolling="no" 
                        frameborder="0" 
                        allowfullscreen="true" 
                        allow="autoplay; clipboard-write; encrypted-media; picture-in-picture; web-share">
                    </iframe>
                    <a href="https://www.facebook.com/share/p/18SUKZpnhZ/">View Link to Post</a>
                </div>
            </div>

            <!-- Slide 2 -->
            <div class="swiper-slide">
                <div class="announcement-box">
                    <h3>Town Hall Meeting</h3>
                    <iframe 
                        title="Town Hall Meeting Facebook Post"
                        loading="lazy"
                        src="https://www.facebook.com/plugins/post.php?href=https%3A%2F%2Fwww.facebook.com%2FAdZUStudentAffairs%2Fposts%2Fpfbid0v6s5dvDtrooDS7h726uh8tYAdkB995mMPZeDh8kt2Kp5251wT9UQCMAvELDKACsDl&show_text=true&width=500" 
                        width="500" 
                        height="792" 
                        style="border:none;overflow:visible" 
                        scrolling="no" 
                        frameborder="0" 
                        allowfullscreen="true" 
                        allow="autoplay; clipboard-write; encrypted-media; picture-in-picture; web-share">
                    </iframe>
                    <a href="https://www.facebook.com/share/p/1Xz8EMhkcB/">View Link to Post</a>
                </div>
            </div>

            <!-- Slide 3 -->
            <div class="swiper-slide">
                <div class="announcement-box">
                    <h3>College Entrance Test for 1st Year Students</h3>
                    <iframe 
                        title="College Entrance Test Facebook Post"
                        loading="lazy"
                        src="https://www.facebook.com/plugins/post.php?href=https%3A%2F%2Fwww.facebook.com%2Fateneodezamboangauniversity%2Fposts%2Fpfbid02W24kfLd9jzcgDXorwHyUfWa51nwnvkcJSgfDkx9Ffb5VwgSneTPDBD5xLig6UXgkl&show_text=true&width=500" 
                        width="500" 
                        height="792" 
                        style="border:none;overflow:visible" 
                        scrolling="no" 
                        frameborder="0" 
                        allowfullscreen="true" 
                        allow="autoplay; clipboard-write; encrypted-media; picture-in-picture; web-share">
                    </iframe>
                    <a href="https://www.facebook.com/share/p/17uagpJwiS/">View Link to Post</a>
                </div>
            </div>

            <!-- Slide 4 -->
            <div class="swiper-slide">
                <div class="announcement-box">
                    <h3>SNAPSHOTS | AdZU SOM Levels Up </h3>
                    <iframe 
                        title="AdZU SOM Levels Up Facebook Post"
                        loading="lazy"
                        src="https://www.facebook.com/plugins/post.php?href=https%3A%2F%2Fwww.facebook.com%2Fateneodezamboangauniversity%2Fposts%2Fpfbid0dpwYcXqpht7tRKEKEmLVpPtfYQoibcjKq5rfPHPCbduHG8d5fsnC3pFRxdqJRJ7jl&show_text=true&width=500" 
                        width="500" 
                        height="792" 
                        style="border:none;overflow:visible" 
                        scrolling="no" 
                        frameborder="0" 
                        allowfullscreen="true" 
                        allow="autoplay; clipboard-write; encrypted-media; picture-in-picture; web-share">
                    </iframe>
                    <a href="https://www.facebook.com/share/p/1Ae9dJ5pr3/">View Link to Post</a>
                </div>
            </div>

            <!-- Slide 5 -->
            <div class="swiper-slide">
                <div class="announcement-box">
                    <h3>AdZU OPPORTUNITIES </h3>
                    <iframe 
                        title="AdZU Opportunities Facebook Post"
                        loading="lazy"
                        src="https://www.facebook.com/plugins/post.php?href=https%3A%2F%2Fwww.facebook.com%2Fateneodezamboangauniversity%2Fposts%2Fpfbid02FivUQC7K98x7q61eixMJiY1Je68GsSk443EzM7TymCLK35ckz4fjXSkLjKgz3ksEl&show_text=true&width=500" 
                        width="500" 
                        height="792" 
                        style="border:none;overflow:visible" 
                        scrolling="no" 
                        frameborder="0" 
                        allowfullscreen="true" 
                        allow="autoplay; clipboard-write; encrypted-media; picture-in-picture; web-share">
                    </iframe>
                    <a href="https://www.facebook.com/share/p/1C69o4bPaq/">View Link to Post</a>
                </div>
            </div>

        </div>

        <!-- Swiper Navigation Buttons -->
        <div class="swiper-button-next"></div>
        <div class="swiper-button-prev"></div>

        <!-- Swiper Pagination -->
        <div class="swiper-pagination"></div>
    </div>
</section>


    <footer class="footer" id="footer">
        <div class="footer-content">
            <img src="assets/OSAfooter.png" alt="OSA Logo" class="footer-logo">
            <div class="footer-text">
                <img src="assets/icon_school.png" class="footer-icon">
                <p>The Office of Student Affairs
                    G/F Canisius Hall Canisius-Gonzaga Building,<br>
                    Ateneo de Zamboanga University, La Purisima Street
                </p>
            </div>
            <div class="footer-feedback">
            <p>Send us your feedback through clicking the icon below</p>

                <?php if (isset($_SESSION['role']) && $_SESSION['role'] !== 'admin'): ?>
                <a href="forms/FeedbackForm/feedbackForm.php">
                <?php endif; ?>
                <img src="assets/fback_icon.png" alt="FeedbackForm" class="icon">

    </a>
            </div>
        </div>
        <div class="footer-icons">
            <div class="icon-text">
                <img src="assets/fblogo.png" alt="Facebook" class="icon">
                <p>Office of Student Affairs - Ateneo de Zamboanga</p>
            </div>
            <div class="icon-text">
                <img src="assets/telephone.png" alt="Telephone" class="icon">
                <p>991 - 0871 local 2204</p>
            </div>
        </div>
    </footer>

    <script>
        // Array of background images
        const backgroundImages = [
            "assets/bc_building.jpg",
            "assets/xavier_hall.jpg",
            "assets/univ_church.jpg",
            "assets/centennial_tree.jpg",
            "assets/mpcc.jpg"
        ];
    
        // Current slide index
        let currentSlideIndex = 0;
    
        function changeBackground() {
            const slideshowBackground = document.querySelector(".slideshow-background");

            slideshowBackground.style.backgroundImage = `url(${backgroundImages[currentSlideIndex]})`;
    
            currentSlideIndex++;
            if (currentSlideIndex >= backgroundImages.length) {
                currentSlideIndex = 0; // Reset to the first image
            }
    
            // Change background every 15 seconds
            setTimeout(changeBackground, 15000);
        }
    
        // Initialize the slideshow
        window.onload = changeBackground;

    </script>

    <script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>
    
    <script>
    var swiper = new Swiper(".mySwiper", {
        slidesPerView: 1,
        spaceBetween: 10,
        navigation: {
            nextEl: ".swiper-button-next",
            prevEl: ".swiper-button-prev"
        },
        pagination: {
            el: ".swiper-pagination",
            clickable: true
        },
        breakpoints: {
            768: {
                slidesPerView: 2,
                spaceBetween: 20
            },
            1024: {
                slidesPerView: 3,
                spaceBetween: 30
            }
        }
    });

    
    </script>

    
    
</body>
</html>
</body>
</html>
    
