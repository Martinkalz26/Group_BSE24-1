<x-filament-widgets::widget>
    <x-filament::section>
        <div class="slideshow-container">
            @foreach($images as $image)
                <div class="mySlides fade">
                    <img src="{{ asset($image) }}" style="width:100%">
                </div>
            @endforeach

            <a class="prev" onclick="plusSlides(-1)">&#10094;</a>
            <a class="next" onclick="plusSlides(1)">&#10095;</a>
        </div>

        <style>
            .slideshow-container {
                position: relative;
                max-width: 100%;
            }
            .mySlides {
                display: none;
            }
            .prev, .next {
                cursor: pointer;
                position: absolute;
                top: 50%;
                width: auto;
                padding: 16px;
                margin-top: -22px;
                color: white;
                font-weight: bold;
                font-size: 18px;
                transition: 0.6s ease;
                border-radius: 0 3px 3px 0;
                user-select: none;
            }
            .next {
                right: 0;
                border-radius: 3px 0 0 3px;
            }
            .prev:hover, .next:hover {
                background-color: rgba(0,0,0,0.8);
            }
        </style>
    </x-filament::section>

    <script>
        document.addEventListener('DOMContentLoaded', (event) => {
            let slideIndex = 1;
            showSlides(slideIndex);

            document.querySelectorAll('.prev').forEach(el => {
                el.addEventListener('click', () => {
                    plusSlides(-1);
                });
            });

            document.querySelectorAll('.next').forEach(el => {
                el.addEventListener('click', () => {
                    plusSlides(1);
                });
            });

            function plusSlides(n) {
                showSlides(slideIndex += n);
            }

            function showSlides(n) {
                let i;
                let slides = document.getElementsByClassName("mySlides");
                if (n > slides.length) {slideIndex = 1}
                if (n < 1) {slideIndex = slides.length}
                for (i = 0; i < slides.length; i++) {
                    slides[i].style.display = "none";
                }
                slides[slideIndex-1].style.display = "block";
            }
        });
    </script>
</x-filament-widgets::widget>
