<?php
$conn = new mysqli("localhost", "root", "", "country");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Run query
$sql = "SELECT * FROM countries";
$result = $conn->query($sql);

// Check query success FIRST (this fixes your error)
if (!$result) {
    die("Query failed: " . $conn->error);
}

$countries = [];

// Fetch data safely
while ($row = $result->fetch_assoc()) {
    $countries[] = $row;
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Country Carousel</title>
    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: #f2f2f2;
        }

        .top-bar {
            width: 100%;
            background: #0315df;
            padding: 20px;
            text-align: center;
        }

        .top-bar input {
            padding: 10px;
            width: 300px;
            margin-right: 10px;
            font-size: 16px;
        }

        .top-bar button {
            padding: 10px 20px;
            font-size: 16px;
            cursor: pointer;
            background: #4CAF50;
            color: white;
            border: none;
        }

        .container {
            width: 900px;
            margin: 40px auto;
            background: white;
            padding: 20px;
            border: 1px solid #1303a4;
            position: relative;
        }

        .carousel {
            overflow: hidden;
            width: 100%;
            position: relative;
        }

        .slides {
            display: flex;
            transition: transform 0.5s ease-in-out;
        }

        .slide {
            min-width: 100%;
            text-align: center;
        }

        .slide img {
            width: 100%;
            max-width: 400px;
            height: auto;
            border: 2px solid #333;
        }

        .country-info {
            margin-top: 15px;
            font-size: 20px;
            padding: 15px;
            width: 400px;
            margin: 0 auto;
            text-align: center;
        }

        .nav-btn {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            font-size: 40px;
            background: rgba(0, 0, 0, 0.5);
            color: white;
            padding: 10px 15px;
            cursor: pointer;
            border-radius: 5px;
        }

        .prev { left: 10px; }
        .next { right: 10px; }

        .nav-btn:hover {
            background: rgba(0, 0, 0, 0.8);
        }
    </style>
</head>

<body>

<div class="top-bar">
    <input type="text" id="searchInput" placeholder="Search country...">
    <button onclick="handleSearch()">Search</button>
</div>

<div class="container">
    <div class="carousel">
        <div class="slides" id="slides">

            <?php if (count($countries) > 0): ?>
                <?php foreach ($countries as $country): ?>
                    <div class="slide" data-country="<?= strtolower(htmlspecialchars($country['name'])) ?>">
                        <img src="<?= htmlspecialchars($country['flag_url']) ?>" alt="Flag">
                        <div class="country-info">
                            <strong><?= htmlspecialchars($country['name']) ?></strong>
                            <span>Capital: <?= htmlspecialchars($country['capital']) ?></span>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p style="text-align:center;">No countries found.</p>
            <?php endif; ?>

        </div>

        <div class="nav-btn prev" onclick="moveSlide(-1)">&#10094;</div>
        <div class="nav-btn next" onclick="moveSlide(1)">&#10095;</div>
    </div>
</div>

<script>
let index = 0;
let totalSlides = 0;
let allSlides = [];

function initCarousel() {
    const slidesContainer = document.getElementById('slides');
    if (!slidesContainer) return;

    allSlides = document.querySelectorAll('.slide');
    totalSlides = allSlides.length;

    updateSlidePosition();
}

function moveSlide(step) {
    if (totalSlides === 0) return;

    index += step;

    if (index >= totalSlides) index = 0;
    if (index < 0) index = totalSlides - 1;

    updateSlidePosition();
}

function updateSlidePosition() {
    const slides = document.getElementById('slides');
    if (!slides) return;

    slides.style.transform = `translateX(-${index * 100}%)`;
}

function handleSearch() {
    const input = document.getElementById('searchInput');
    const value = input.value.toLowerCase().trim();

    if (value === '') {
        index = 0;
        updateSlidePosition();
        return;
    }

    let foundIndex = -1;

    allSlides.forEach((slide, i) => {
        const name = slide.dataset.country;
        if (name.includes(value) && foundIndex === -1) {
            foundIndex = i;
        }
    });

    if (foundIndex !== -1) {
        index = foundIndex;
        updateSlidePosition();
    } else {
        alert("Country not found");
    }
}

document.addEventListener('keydown', function(e) {
    if (e.key === 'ArrowLeft') moveSlide(-1);
    if (e.key === 'ArrowRight') moveSlide(1);
});

document.addEventListener('DOMContentLoaded', initCarousel);
</script>

</body>
</html>