@extends('frontend.layouts.app')

@section('title', 'Photo Gallery')

@section('content')
    <section class="py-5">
        <div class="container">
            <h1 class="section-title mb-4">Photo Gallery</h1>
            <div class="row g-4 gallery-grid">
                @forelse($items as $item)
                    <div class="col-md-6 col-lg-3" data-aos="zoom-in">
                        <div class="card h-100 gallery-card">
                            @if ($item->image)
                                <img src="{{ asset('storage/' . $item->image) }}" class="card-img-top gallery-thumb"
                                    data-index="{{ $loop->index }}" data-src="{{ asset('storage/' . $item->image) }}"
                                    data-title="{{ $item->title }}" alt="{{ $item->title }}">
                            @endif
                            <div class="card-body">
                                <h6>{{ $item->title }}</h6>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12">
                        <p>No gallery photos yet.</p>
                    </div>
                @endforelse
            </div>
            <div class="mt-4">{{ $items->links() }}</div>
        </div>
    </section>

    <div class="gallery-lightbox" id="galleryLightbox" aria-hidden="true" role="dialog" aria-label="Photo viewer">
        <button type="button" class="gallery-close" id="galleryClose" aria-label="Close gallery">
            <i class="bi bi-x-lg"></i>
        </button>
        <button type="button" class="gallery-nav gallery-prev" id="galleryPrev" aria-label="Previous photo">
            <i class="bi bi-chevron-left"></i>
        </button>
        <div class="gallery-content">
            <img src="" alt="" id="galleryImage">
            <div class="gallery-caption">
                <span id="galleryTitle"></span>
                <span class="gallery-counter" id="galleryCounter"></span>
            </div>
        </div>
        <button type="button" class="gallery-nav gallery-next" id="galleryNext" aria-label="Next photo">
            <i class="bi bi-chevron-right"></i>
        </button>
    </div>
@endsection

@push('styles')
    <style>
        .gallery-grid {
            position: relative;
        }

        .gallery-thumb {
            cursor: zoom-in;
            transition: transform 0.25s ease, filter 0.25s ease;
            object-fit: cover;
            height: 230px;
            width: 100%;
        }

        .gallery-thumb:hover {
            transform: scale(1.02);
            filter: brightness(1.02);
        }

        .gallery-lightbox {
            position: fixed;
            inset: 0;
            display: none;
            align-items: center;
            justify-content: center;
            padding: 1.5rem;
            background: rgba(8, 15, 29, 0.85);
            backdrop-filter: blur(6px);
            z-index: 2000;
        }

        .gallery-lightbox.active {
            display: flex;
        }

        .gallery-content {
            position: relative;
            max-width: 1000px;
            width: 100%;
            max-height: 90vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 1rem;
        }

        .gallery-content img {
            max-width: 100%;
            max-height: 80vh;
            border-radius: 1rem;
            box-shadow: 0 24px 80px rgba(0, 0, 0, 0.35);
        }

        .gallery-caption {
            display: flex;
            align-items: center;
            justify-content: space-between;
            width: 100%;
            color: #f8fafc;
            font-size: 0.95rem;
            gap: 1rem;
        }

        .gallery-counter {
            opacity: 0.75;
        }

        .gallery-nav,
        .gallery-close {
            position: absolute;
            border: none;
            background: rgba(15, 23, 42, 0.75);
            color: #ffffff;
            width: 3.2rem;
            height: 3.2rem;
            border-radius: 999px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: transform 0.2s ease, background 0.2s ease;
        }

        .gallery-nav:hover,
        .gallery-close:hover {
            transform: translateY(-2px);
            background: rgba(15, 23, 42, 0.9);
        }

        .gallery-prev {
            left: 1rem;
        }

        .gallery-next {
            right: 1rem;
        }

        .gallery-close {
            top: 1rem;
            right: 1rem;
        }

        @media (max-width: 768px) {

            .gallery-nav,
            .gallery-close {
                width: 2.8rem;
                height: 2.8rem;
            }

            .gallery-caption {
                flex-direction: column;
                align-items: flex-start;
            }
        }
    </style>
@endpush

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const thumbs = Array.from(document.querySelectorAll('.gallery-thumb'));
            const lightbox = document.getElementById('galleryLightbox');
            const imageEl = document.getElementById('galleryImage');
            const titleEl = document.getElementById('galleryTitle');
            const counterEl = document.getElementById('galleryCounter');
            const closeBtn = document.getElementById('galleryClose');
            const prevBtn = document.getElementById('galleryPrev');
            const nextBtn = document.getElementById('galleryNext');
            let currentIndex = 0;

            if (!thumbs.length || !lightbox) {
                return;
            }

            const updateLightbox = (index) => {
                const item = thumbs[index];
                if (!item) {
                    return;
                }

                currentIndex = index;
                imageEl.src = item.dataset.src;
                imageEl.alt = item.dataset.title || 'Gallery photo';
                titleEl.textContent = item.dataset.title || '';
                counterEl.textContent = `${index + 1} / ${thumbs.length}`;
                lightbox.classList.add('active');
                lightbox.setAttribute('aria-hidden', 'false');
            };

            const closeLightbox = () => {
                lightbox.classList.remove('active');
                lightbox.setAttribute('aria-hidden', 'true');
                imageEl.src = '';
            };

            thumbs.forEach((thumb, index) => {
                thumb.addEventListener('click', () => {
                    updateLightbox(index);
                });
            });

            closeBtn.addEventListener('click', closeLightbox);
            lightbox.addEventListener('click', (event) => {
                if (event.target === lightbox) {
                    closeLightbox();
                }
            });

            prevBtn.addEventListener('click', () => {
                updateLightbox((currentIndex - 1 + thumbs.length) % thumbs.length);
            });

            nextBtn.addEventListener('click', () => {
                updateLightbox((currentIndex + 1) % thumbs.length);
            });

            document.addEventListener('keydown', (event) => {
                if (!lightbox.classList.contains('active')) {
                    return;
                }

                if (event.key === 'Escape') {
                    closeLightbox();
                }
                if (event.key === 'ArrowLeft') {
                    updateLightbox((currentIndex - 1 + thumbs.length) % thumbs.length);
                }
                if (event.key === 'ArrowRight') {
                    updateLightbox((currentIndex + 1) % thumbs.length);
                }
            });
        });
    </script>
@endpush
