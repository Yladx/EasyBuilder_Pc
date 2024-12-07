<style>
 /* General Styling */
 .intro-container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 20px;
}

/* Header Section */
.lead {
    text-align: center;
    font-size: 1.2rem; /* Reduced font size */
    color: #555; /* Medium gray */
    margin-bottom: 40px;
}

/* Introduction Section */
.intro-section {
    padding: 40px 20px;
    background: linear-gradient(135deg, #f8f8f8, #e8e8e8); /* Subtle grayscale gradient */
    border-radius: 12px;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1); /* Subtle shadow */
    transition: transform 0.3s ease, box-shadow 0.3s ease; /* Smooth hover animation */
    margin-bottom: 40px;
}

.intro-section:hover {
    transform: translateY(-10px); /* Lift effect */
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15); /* Enhanced shadow */
}

.intro-section h2 {
    font-size: 2rem; /* Reduced from 2.5rem */
    font-weight: bold;
    color: #000; /* Neutral dark color */
    margin-bottom: 20px;
    border-left: 5px solid #555; /* Darker gray strip */
    padding-left: 15px;
}

.intro-section p {
    font-size: 1rem; /* Reduced from 1.2rem */
    color: #000; /* Neutral medium text color */
    line-height: 1.6; /* Adjusted for smaller font */
}

/* Highlighted Text */
.intro-section strong {
    color: #000; /* Dark text color */
    font-weight: bold;
    text-decoration: underline;
}

/* Learn More Link */
.intro-section a {
    color: #000;
    text-decoration: none;
    font-weight: bold;
    border-bottom: 1px solid #444; /* Underline effect */
    transition: color 0.2s ease, border-color 0.2s ease;
}

.intro-section a:hover {
    color: #000; /* Darker text on hover */
    border-color: #000;
}

/* Section Headers */
.intro-container section h2 {
    font-size: 1.8rem; /* Reduced from 2rem */
    font-weight: bold;
    color: #333;
    text-align: center;
    margin-bottom: 20px;
}

/* List Styling */
.intro-container ul, ol {
    margin: 20px 0;
    padding-left: 30px; /* Adjusted for smaller text */
}

.intro-container ul li, ol li {
    margin-bottom: 8px; /* Slightly reduced spacing */
    font-size: 0.9rem; /* Reduced font size */
    color: #000;
}

.intro-container ul li strong, ol li strong {
    color: #222;
    font-weight: bold;
}

/* Benefits Section */
.benefits {
    background: #fff; /* White background for contrast */
    border-radius: 12px;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1); /* Subtle shadow */
    padding: 30px; /* Reduced padding */
    margin-bottom: 40px;
}

.benefits ul {
    list-style: none; /* Remove default bullets */
    padding-left: 0;
}

.benefits ul li {
    display: flex;
    align-items: center;
    margin-bottom: 10px; /* Reduced spacing */
    font-size: 0.9rem; /* Reduced font size */
}

.benefits ul li::before {
    content: '\f058'; /* FontAwesome check icon */
    font-family: 'FontAwesome';
    color: #333; /* Dark gray for checkmark */
    margin-right: 8px;
    font-size: 1rem; /* Reduced icon size */
}

/* Hover Animations */
.intro-container section:hover h2 {
    color: #000; /* Darker heading on hover */
}

.intro-container section:hover p {
    color: #333; /* Slightly darker text */
}




</style>
<main class="intro-container">
    <h1 class="display-4 text-center mb-4 fw-bold">Welcome to  EasyBuilder Learning Platform </h1>
    <p class="lead text-center mb-4">Your gateway to mastering the world of computers and technology.</p>

    <section class="intro-section">
        <h2>About Our Learning Platform</h2>
        <p>
            In this part of the website, you can learn more about the nuances of PC building and explore how our system simplifies the process for users of all experience levels. From selecting compatible components to ensuring optimal performance, our platform is designed to guide you every step of the way. By breaking down the complexities of PC building, we make it accessible and enjoyable for everyone, whether you're a seasoned enthusiast or a beginner. Additionally, you’ll discover how our system goes beyond just hardware selection, offering insights into creating reliable builds tailored to your needs. This journey into PC building not only enhances your technical knowledge but also equips you with the confidence to customize a system that works perfectly for you.        </p>
    </section>

    <section class="learning-purposes mt-4">
        <h2>Why Learn About Computers?</h2>
        <p>
            Understanding computers is no longer optional in today's digital world. From everyday tasks to complex problem-solving, the knowledge of how computers work is essential. With our learning modules, you will:
        </p>
        <ul>
            <li><strong>Understand Basic Computer Functions:</strong> Learn how to use operating systems, hardware, and software effectively.</li>
            <li><strong>Enhance Technical Skills:</strong> Dive deep into computer programming, system troubleshooting, and more.</li>
            <li><strong>Stay Ahead in the Digital Era:</strong> Equip yourself with the latest knowledge about emerging technologies like AI, cloud computing, and cybersecurity.</li>
        </ul>
    </section>


    <section class="benefits mt-5">
        <h2>Key Benefits</h2>
        <p>
            By using Learning Modules, you’ll gain practical skills that can help you advance in your career or personal projects. Our interactive lessons make learning enjoyable and engaging.
        </p>
        <ul>
            <li><strong>Interactive Learning:</strong> Learn by doing with hands-on tutorials .</li>
            <li><strong>Accessible Anytime:</strong> Access modules from any device, anytime, and anywhere.</li>
            <li><strong>Comprehensive Content:</strong> From basic concepts to advanced techniques, we cover everything!</li>
        </ul>
    </section>
</main>
