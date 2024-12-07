<x-app-layout>



    @include('home.carouselads')


    @include('home.getstarted')



    @include('home.about')






    @include('components.footer')



    <style>
        .aboutus {
        background-color: #000; /* Black background */
        color: #fff; /* White text */
        text-align: center; /* Center align text */
        padding: 50px 20px; /* Add padding for spacing */
    }

    .aboutus h1 {
        font-size: 2.5rem; /* Large heading size */
        font-weight: bold; /* Bold text for heading */
        margin-bottom: 1rem; /* Space below heading */
    }

    .aboutus-text {
        font-size: 1rem; /* Base font size */
        font-weight: 400; /* Normal weight for paragraph */
        line-height: 1.8; /* Increase line height for readability */
        max-width: 800px; /* Limit text width for better reading experience */
        margin: 0 auto; /* Center align the text */
    }
    .letgetstarted {
        background-color: #000; /* Black background */
        color: #fff; /* White text */
        padding: 50px 0; /* Vertical padding */
    }

    .image-container img {
        width: 100%;
        max-width: 600px; /* Ensure a maximum width */
        height: auto;
        border-radius: 10px; /* Rounded corners */
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.5); /* Subtle shadow for emphasis */
    }

    .text-container h1 {
        font-size: 2rem; /* Large heading size */
        font-weight: bold; /* Bold text */
    }

    .text-container p {
        font-size: 1rem; /* Base font size */
        line-height: 1.8; /* Line height for readability */
    }

    .btn-light {
        background-color: #fff;
        color: #000;
        border: none;
        box-shadow: 0 4px 10px rgba(255, 255, 255, 0.3); /* Button shadow */
        transition: all 0.3s ease-in-out;
    }

    .btn-light:hover {
        background-color: #f8f9fa;
        box-shadow: 0 4px 15px rgba(255, 255, 255, 0.5); /* Shadow on hover */
        color: #000;
    }

    </style>

</x-app-layout>
