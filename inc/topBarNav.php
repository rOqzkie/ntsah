<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NEMSU Archiving Hub</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        .top-bar {
            display: flex;
            justify-content: space-between;
            background-color: #17445a;
            padding: 10px 20px;
            color: white;
            font-size: 14px;
        }
        .top-bar a {
            color: white;
            text-decoration: none;
            margin-right: 10px;
        }
        nav {
            display: flex;
            justify-content: space-between;
            align-items: center;
            /*position: absolute;*/
            width: 100%;
            /*background: rgba(0, 47, 95, 0.3); /* Light navy with transparency */
            background-color: #17445abf;
            backdrop-filter: blur(10px); /* Strong blur for glass effect */
            padding: 8px 15px; /* Reduced padding for smaller height */
            min-height: 50px; /* Ensures a smaller height */
            color: white;
            /*box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1); /* Soft shadow for depth */
            transition: background 0.3s ease-in-out, backdrop-filter 0.3s ease-in-out;
        }

        .logo {
            font-size: 24px;
            font-weight: bold;
            color: white;
        }
        nav ul {
            list-style: none;
            display: flex;
            padding: 0;
            margin: 0;
        }
        nav ul li {
            margin-left: 20px;
        }
        nav ul li a {
            color: white;
            text-decoration: none;
        }
        .sign-in {
            background: #FFA500;
            border: none;
            padding: 8px 12px;
            cursor: pointer;
        }
        
        body {
            font-family: Arial, sans-serif;
            text-align: center;
            color: blue;
            margin: 0;
            padding: 0;
        }
        .banner {
            padding: 50px 20px;
            background: url('./uploads/bg-ntfs.jpg') no-repeat center center;
            background-size: cover;
            color: #fff;
        }
/*
        .banner {
            padding: 50px 20px;
            background: linear-gradient(to right, #f0f4ff, #ffffff); 
            
            background-size: cover;
            color: #1a237e; 
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
        }
*/
        .banner h1 {
            font-size: 32px;
            font-weight: bold;
            margin: 0;
            /*background: linear-gradient(45deg, #007BFF, #00C3FF); /* Blue gradient */
            background: linear-gradient(45deg, #007BFF, #00C3FF, #ff00ff, #ff6600);
            background-size: 300% 300%;
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent; /* Makes the gradient apply to text */
            animation: gradientShift 4s infinite linear;
        }
        
        @keyframes gradientShift {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }

        .banner p {
            font-size: 18px;
            margin: 5px 0;
        }

        .teal {
            color: #20CFCF;
            font-weight: bold;
            font-size: 30px;
        }

        .search-bar {
            display: flex;
            align-items: center;
            max-width: 700px;
            margin: 20px auto;
            background-color: white;
            border-radius: 5px;
            overflow: hidden;
            border: 1px solid #ccc;
            position: relative;
            gap: 5px;
            /*display: inline-block;*/
        }

        .search-bar select {
            background-color: #d3d3d3;
            border: none;
            padding: 10px;
            font-size: 16px;
            font-style: bold;
            appearance: none; /* Removes default arrow */
            -webkit-appearance: none;
            -moz-appearance: none;
            width: 30%;
            border-radius: 5px;
            cursor: pointer;
            padding-right: 40px; /* Space for the custom arrow */
            /* Custom dropdown arrow */
            background-image: url('data:image/svg+xml;charset=UTF-8,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="%23007BFF"><path d="M7 10l5 5 5-5H7z"/></svg>');
            background-repeat: no-repeat;
            background-position: calc(100% - 15px) center; /* Centers vertically */
            background-size: 30px;
        }

        .search-bar input {
            flex: 1;
            padding: 12px;
            border: none;
            font-size: 16px;
            outline: none;
        }

        .search-bar button {
            background-color: #d69e2e;
            border: none;
            padding: 12px 16px;
            cursor: pointer;
            font-size: 16px;
        }

        .search-bar button:hover {
            background-color: #b8860b;
        }

        .search-options {
            display: flex;
            justify-content: center;
            gap: 15px;
            margin-top: 15px;
        }

        .search-options a {
            background-color: #1a2b3c;
            color: white;
            padding: 10px 15px;
            text-decoration: none;
            border-radius: 5px;
            font-size: 14px;
        }

        .search-options a:hover {
            background-color: #374f67;
        }
        .nav-icon {
            max-width: 50px; /* Maximum size */
            width: 10vw; /* Adjusts based on viewport width */
            height: auto; /* Keeps aspect ratio */
        }
        .filter-options {
            display: flex;
            justify-content: center;
            gap: 15px;
            margin-top: 15px;
        }
        .warning-placeholder::placeholder {
            color: red;
            font-style: italic;
        }
        #submit-btn {
            transition: all 0.3s ease-in-out;
        }
        #submit-btn:hover {
            background-color: #0d6efd;
            transform: scale(1.05);
            box-shadow: 0px 4px 10px rgba(0, 123, 255, 0.3);
        }
        #search-input, #college-select {
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 4px;
            width: 100%;
        }
        #search-btn {
            background: none;
            border: none;
            font-size: 18px;
            cursor: pointer;
        }
        #college-list-container {
            border: 1px solid #ccc;
            border-radius: 4px;
            background: white;
            cursor: pointer;
        }

        .college-selected {
            padding: 8px;
            background: #fff;
            border-radius: 4px;
        }

        .college-list {
            display: none;
            list-style: none;
            margin: 0;
            padding: 0;
            position: absolute;
            width: 100%;
            background: white;
            border: 1px solid #ccc;
            border-top: none;
            z-index: 10;
        }

        .college-list.show {
            display: block;
        }

        .college-list li {
            padding: 8px;
        }

        .college-list a {
            display: block;
            padding: 8px;
            text-decoration: none;
            color: black;
        }

        .college-list a:hover {
            background: #f0f0f0;
        }
        /* Dropdown Styles */
        .dropdown-container {
            border: 1px solid #ccc;
            border-radius: 4px;
            background: white;
            cursor: pointer;
            position: relative;
            width: 200px;
        }

        .dropdown-selected {
            padding: 8px;
            background: #fff;
            border-radius: 4px;
            font-weight: bold;
        }

        .dropdown-list {
            display: none;
            list-style: none;
            margin: 0;
            padding: 0;
            position: absolute;
            width: 100%;
            background: white;
            border: 1px solid #ccc;
            border-top: none;
            z-index: 10;
            max-height: 200px;
            overflow-y: auto;
        }

        .dropdown-list.show {
            display: block;
        }

        .dropdown-list li {
            padding: 8px;
        }

        .dropdown-list a {
            display: block;
            padding: 8px;
            text-decoration: none;
            color: black;
            font-size: 14px;
            transition: background 0.2s;
        }

        .dropdown-list a:hover {
            background: #007bff;
            color: white;
        }
        #slideshow {
            transition: opacity 1s ease-in-out;
        }
        #suggestions-box {
            position: absolute;
            background: white;
            border: 1px solid #ccc;
            width: calc(100% - 2px); /* Match input width */
            max-height: 200px;
            overflow-y: auto;
            display: none;
            z-index: 1000;
            top: 100%; /* Position right below input */
            left: 0;
        }

        .suggestion {
            padding: 8px;
            cursor: pointer;
        }

        .suggestion:hover {
            background: #f0f0f0;
        }
        .student-img {
            object-fit: cover;
            height: 180px;
            width: 180px;
            border: 5px solid #0d6efd;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        }
    </style>
</head>
<body>
    <header>
        <div class="top-bar">
            <div class="left-links">
            </div>
            <div class="right-links">
                <?php if($_settings->userdata('type') == 3): ?>
                    <a href="./?page=profile-adviser"
                    <span class="mx-2"><img src="<?= validate_image($_settings->userdata('avatar')) ?>" alt="User Avatar" id="student-img-avatar" title="Profile" class="img-fluid rounded-circle student-img"></span>
                    </a>
                    <span class="mx-2">Welcome, <?= !empty($_settings->userdata('firstname')) ? $_settings->userdata('firstname')." ".$_settings->userdata('lastname') : $_settings->userdata('username') ?></span>
                    <span class="mx-1"><a href="<?= base_url.'classes/Login.php?f=adviser_logout' ?>"><i class="fa fa-power-off text-danger"></i></a></span>
                <?php endif; ?>
                <?php if($_settings->userdata('type') == 2): ?>
                    <a href="./?page=profile"
                    <span class="mx-2"><img src="<?= validate_image($_settings->userdata('avatar')) ?>" alt="User Avatar" id="student-img-avatar" title="Profile" class="img-fluid rounded-circle student-img"></span>
                    </a>
                    <span class="mx-2">Welcome, <?= !empty($_settings->userdata('firstname')) ? $_settings->userdata('firstname')." ".$_settings->userdata('lastname') : $_settings->userdata('username') ?></span>
                    <span class="mx-1"><a href="<?= base_url.'classes/Login.php?f=student_logout' ?>"><i class="fa fa-power-off text-danger" title="Logout"></i></a></span>
                <?php endif; ?>
            </div>
        </div>
        <nav class="navbar navbar-expand-lg navbar-dark bg-primary px-3">
            <a class="navbar-brand fw-bold text-white" href="./" title="Back to Home">
                📂 NEMSU Archiving Hub
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMenu" aria-controls="navMenu" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse justify-content-end" id="navMenu">
                <ul class="navbar-nav align-items-lg-center gap-2">
                    <?php if ($_settings->userdata('type') == 3): ?>
                    <li class="nav-item">
                        <a href="./?page=submit-archive" class="btn btn-light text-primary px-4 py-2 rounded-pill fw-bold shadow-sm <?= isset($page) && $page == 'submit-archive' ? "active" : "" ?>" id="submit-btn">
                            <i class="fas fa-upload me-2"></i> Upload Thesis|Feasibility Study
                        </a>
                    </li>
                    <?php endif; ?>
                </ul>
            </div>
        </nav>
        <div class="banner">
            <center><h1 id="slideshow">Empowering Research, Preserving Knowledge</h1>
            <?php
                $totalarchives = $conn->query("SELECT * FROM archive_list where status = 1")->num_rows;
            ?>
            <p>SEARCH <span class="teal"><?php echo $totalarchives; ?></span> TOTAL NO. OF ARCHIVES</p>
            </center>
            <div class="search-bar">
                <select id="filter-select">
                    <option value="all">All</option>
                    <option value="college">College</option>
                </select>
                <input type="search" id="search-input" placeholder="Search for keywords, title, author, discipline..." value="<?= isset($_GET['q']) ? $_GET['q'] : '' ?>" required autocomplete="off">
                <div id="suggestions-box" class="suggestions-dropdown"></div>
                
                <!--<div style="position: relative; width: 100%;">
                    <input type="search" id="search-input" placeholder="Search..." value="<?= isset($_GET['q']) ? $_GET['q'] : '' ?>" required autocomplete="off">
                    <div id="suggestions-box"></div>
                </div>-->
                <select id="college-select" style="display: none;" name="college">
                    <option value="">Select College</option>
                    <?php 
                        $selectedCollege = isset($_GET['college']) ? $_GET['college'] : ''; // Get the selected value from the URL
                        $college = $conn->query("SELECT * FROM `college_list` where status = 1 order by `name` asc");
                        while($row = $college->fetch_assoc()):
                    ?>
                        <option value="<?= $row['id'] ?>"<?= ($selectedCollege == $row['id']) ? 'selected' : '' ?>><?= ucwords($row['name']) ?>&emsp; - &emsp;<?= ucwords($row['description']) ?></option>
                        <?php endwhile; ?>
                </select>
                <button id="search-btn">🔍</button>
                <!--<a href="javascript:void(0)" class="text-light" id="search_icon">-->
            </div>
            <p id="search-message" style="color: red; display: none; text-align: center; margin-top: 5px;"></p>
            
            <div class="search-options">
               <!-- <a href="#">TOP SEARCHES ➕</a>  -->
            </div>
           
            
            <div class="filter-options disabled">
                <label><input type="radio" name="filter" value="all" <?= (!isset($_GET['filter']) || $_GET['filter'] == 'all') ? 'checked' : '' ?>> All</label>
                <label><input type="radio" name="filter" value="thesis" <?= (isset($_GET['filter']) && $_GET['filter'] == 'thesis') ? 'checked' : '' ?>> Thesis</label>
                <label><input type="radio" name="filter" value="feasibility" <?= (isset($_GET['filter']) && $_GET['filter'] == 'feasibility') ? 'checked' : '' ?>> Feasibility Study</label>
            </div>
        </div>
    </header>
</body>
</html>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function(){
    function performSearch() {
        var query = $('#search-input').val().trim();
        var selectedCollege = $('#college-select').val();
        var currentPage = new URLSearchParams(window.location.search).get("page");

        var url = "./?page=" + (currentPage === "projects_per_college" ? "projects_per_college" : "projects");

        if (query.length > 0) {
            url += "&q=" + encodeURIComponent(query);
        }
        if (selectedCollege && selectedCollege !== "All") {
            url += "&id=" + encodeURIComponent(selectedCollege);
        }

        if (query.length > 0 || selectedCollege !== "All") {
            window.location.href = url;
        } else {
            $('#search-input').attr("placeholder", "Enter keywords, author, or title...").addClass("warning-placeholder");
        }
    }

    $('#search-btn').click(function(event){
        event.preventDefault();
        performSearch();
    });

    $('#search-input').keydown(function(e){
        if(e.which == 13) {
            e.preventDefault();
            performSearch();
        }
    });

    // Filter displayed studies based on search input and selected college
    $('#search-input').on('input', function() {
        var query = $(this).val().toLowerCase();
        var selectedCollege = $('#college-select').val();

        $('.study-item').each(function() {
            var text = $(this).text().toLowerCase();
            var collegeId = $(this).data('college-id'); // Ensure each study has a `data-college-id` attribute

            if (text.includes(query) && (selectedCollege === "All" || collegeId == selectedCollege)) {
                $(this).show();
            } else {
                $(this).hide();
            }
        });
    });

    // Keep selected values after reload
    var urlParams = new URLSearchParams(window.location.search);
    var selectedCollege = urlParams.get("id");
    var query = urlParams.get("q");

    if (selectedCollege) {
        $('#college-select').val(selectedCollege);
    }
    if (query) {
        $('#search-input').val(query);
    }
});
$(document).ready(function(){
    function toggleSearchButton() {
        var searchInput = $('#search-input').val().trim();
        var collegeSelected = $('#college-select').val(); // Get selected college value
        
        // Disable if both search input is empty and no college is selected
        if (searchInput === '' && (!collegeSelected || collegeSelected === '')) {
            $('#search-btn').prop('disabled', true);
        } else {
            $('#search-btn').prop('disabled', false);
        }
    }

    // Disable button on page load if conditions are met
    toggleSearchButton();

    // Enable/disable button on input change or college selection change
    $('#search-input').on('input', toggleSearchButton);
    $('#college-select').on('change', toggleSearchButton);
});
    document.addEventListener("DOMContentLoaded", function() {
        // Search Functionality
        $('#search-icon, .search-bar button').click(function(){
            let query = $('#search-input').val().trim();
            let filter = document.querySelector("input[name='filter']:checked").value; // Get selected filter

            let urlParams = new URLSearchParams();
            if (query.length > 0) {
                urlParams.set("./?page=projects&q=", query);
            }
            if (filter !== "all") {
                urlParams.set("filter", filter);
            }

            window.location.search = urlParams.toString(); // Reload page with search and filter
        });

        // Allow pressing Enter to trigger search
        $('#search-input').keydown(function(e){
            if(e.which == 13) {
                $('#search-icon').click();
            }
        });

        // Filter Change Reloads Page
        document.querySelectorAll("input[name='filter']").forEach(radio => {
            radio.addEventListener("change", function() {
                let filter = this.value;
                let query = $('#search-input').val().trim();
                let urlParams = new URLSearchParams(window.location.search);

                if (query.length > 0) {
                    urlParams.set("./?page=projects&q=", query);
                }
                if (filter === "all") {
                    urlParams.delete("filter");
                } else {
                    urlParams.set("filter", filter);
                }

                window.location.search = urlParams.toString();
            });
        });
    });
    document.addEventListener("DOMContentLoaded", function () {
        let searchInput = document.getElementById('search-input');
        let collegeSelect = document.getElementById('college-select');
        let filterSelect = document.getElementById('filter-select');
        let searchBtn = document.getElementById('search-btn');
        let filterOptions = document.querySelector('.filter-options');
        let filterRadios = document.querySelectorAll('.filter-options input');

        function matchWidth() {
            collegeSelect.style.width = window.getComputedStyle(searchInput).width;
        }

        function updateFilterState() {
            let searchEmpty = searchInput.value.trim() === "";
            let collegeNotSelected = (filterSelect.value === "college" && !collegeSelect.value);

            let shouldDisable = searchEmpty || collegeNotSelected;
            filterOptions.classList.toggle('disabled', shouldDisable);
            filterRadios.forEach(input => input.disabled = shouldDisable);
        }

        matchWidth();
        updateFilterState();

        filterSelect.addEventListener('change', function () {
            if (this.value === 'college') {
                searchInput.style.display = 'none';
                collegeSelect.style.display = 'inline-block';
                matchWidth();
            } else {
                searchInput.style.display = 'inline-block';
                collegeSelect.style.display = 'none';
            }
            updateFilterState();
        });

        searchBtn.addEventListener("click", function () {
            if (filterSelect.value === "college" && collegeSelect.value) {
                // Redirect to the projects_per_college page with the selected college ID
                window.location.href = `./?page=projects_per_college&id=${collegeSelect.value}`;
            }
        });

        searchInput.addEventListener("input", updateFilterState);
        window.addEventListener("resize", matchWidth);
    });
    const texts = [
        "Empowering Research, Preserving Knowledge",
        "Unlock Knowledge, Explore Innovation",
        "Your Gateway to Research Excellence",
        "Discover. Learn. Innovate.",
        "Where Research Meets Future"
    ];
    let index = 0;
    function changeText() {
        const slideshow = document.getElementById("slideshow");
        slideshow.style.opacity = 0; // Fade out
        setTimeout(() => {
            slideshow.innerText = texts[index]; 
            slideshow.style.opacity = 1; // Fade in
            index = (index + 1) % texts.length;
        }, 1000);
    }

    setInterval(changeText, 5000);
/*
    $(document).ready(function() {
    $('#search-input').on('keyup', function() {
        var query = $(this).val();
        if (query.length > 0) {
            $.ajax({
                url: 'fetch_suggestions.php',
                method: 'POST',
                data: { query: query },
                success: function(data) {
                    console.log("Response:", data); // Debugging
                    $('#suggestions-box').html(data).show();
                },
                error: function(xhr, status, error) {
                    console.log("Error:", error);
                }
            });
        } else {
            $('#suggestions-box').hide();
        }
    });

    // Hide suggestions when clicking outside
    $(document).click(function(event) {
        if (!$(event.target).closest('.search-container').length) {
            $('#suggestions-box').hide();
        }
    });

    // Set clicked suggestion as input value
    $(document).on('click', '.suggestion', function() {
        $('#search-input').val($(this).text());
        $('#suggestions-box').hide();
    });
});
*/
</script>