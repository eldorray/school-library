<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0, user-scalable=yes">
    <title>{{ $book->title }} - Baca Online</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.min.js"></script>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background: linear-gradient(135deg, #1a1a2e 0%, #16213e 50%, #0f3460 100%);
            min-height: 100vh;
            min-height: 100dvh;
            display: flex;
            flex-direction: column;
            font-family: 'Segoe UI', system-ui, sans-serif;
            color: #fff;
            overflow: hidden;
        }

        body.fullscreen-mode {
            background: #000;
        }

        .header {
            padding: 0.75rem 1rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            background: rgba(0, 0, 0, 0.3);
            backdrop-filter: blur(10px);
            transition: transform 0.3s ease;
        }

        body.fullscreen-mode .header {
            transform: translateY(-100%);
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            z-index: 100;
        }

        body.fullscreen-mode.show-ui .header {
            transform: translateY(0);
        }

        .header h1 {
            font-size: 1rem;
            font-weight: 600;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            flex: 1;
        }

        .header .author {
            font-size: 0.75rem;
            opacity: 0.7;
        }

        .header-buttons {
            display: flex;
            gap: 0.5rem;
        }

        .header .btn {
            padding: 0.5rem 0.75rem;
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 0.5rem;
            color: #fff;
            text-decoration: none;
            font-size: 0.75rem;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 0.25rem;
        }

        .header .btn:active {
            background: rgba(255, 255, 255, 0.2);
        }

        .pdf-container {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            position: relative;
            touch-action: none;
        }

        .pdf-wrapper {
            position: relative;
            transform-origin: center center;
            transition: transform 0.1s ease-out;
        }

        #pdfCanvas {
            max-width: 100%;
            max-height: calc(100vh - 120px);
            max-height: calc(100dvh - 120px);
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.5);
            border-radius: 4px;
            background: #fff;
            display: block;
        }

        body.fullscreen-mode #pdfCanvas {
            max-height: 100vh;
            max-height: 100dvh;
            border-radius: 0;
        }

        .controls {
            position: fixed;
            bottom: 1rem;
            left: 50%;
            transform: translateX(-50%);
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.75rem 1rem;
            background: rgba(0, 0, 0, 0.8);
            backdrop-filter: blur(10px);
            border-radius: 2rem;
            transition: transform 0.3s ease, opacity 0.3s ease;
            z-index: 100;
        }

        body.fullscreen-mode .controls {
            transform: translateX(-50%) translateY(100%);
            opacity: 0;
        }

        body.fullscreen-mode.show-ui .controls {
            transform: translateX(-50%) translateY(0);
            opacity: 1;
        }

        .controls button {
            width: 2.5rem;
            height: 2.5rem;
            border-radius: 50%;
            border: none;
            background: rgba(255, 255, 255, 0.15);
            color: #fff;
            font-size: 1.25rem;
            cursor: pointer;
            transition: all 0.15s;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .controls button:active {
            background: rgba(255, 255, 255, 0.3);
            transform: scale(0.95);
        }

        .controls button:disabled {
            opacity: 0.3;
            cursor: not-allowed;
        }

        .controls .page-info {
            font-size: 0.875rem;
            min-width: 70px;
            text-align: center;
        }

        .zoom-controls {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            border-left: 1px solid rgba(255, 255, 255, 0.2);
            padding-left: 0.75rem;
            margin-left: 0.25rem;
        }

        .zoom-controls button {
            width: 2rem;
            height: 2rem;
            font-size: 1rem;
        }

        .zoom-info {
            font-size: 0.75rem;
            min-width: 40px;
            text-align: center;
        }

        .loading {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            text-align: center;
        }

        .spinner {
            width: 40px;
            height: 40px;
            border: 3px solid rgba(255, 255, 255, 0.2);
            border-top-color: #fff;
            border-radius: 50%;
            animation: spin 0.8s linear infinite;
            margin: 0 auto 1rem;
        }

        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }

        .hidden {
            display: none !important;
        }

        /* Fullscreen hint */
        .fullscreen-hint {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background: rgba(0, 0, 0, 0.8);
            padding: 1rem 1.5rem;
            border-radius: 1rem;
            font-size: 0.875rem;
            opacity: 0;
            pointer-events: none;
            transition: opacity 0.3s;
            z-index: 200;
        }

        .fullscreen-hint.show {
            opacity: 1;
        }

        /* Double tap indicator */
        .tap-indicator {
            position: fixed;
            width: 60px;
            height: 60px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.3);
            pointer-events: none;
            opacity: 0;
            transform: scale(0);
            z-index: 150;
        }

        .tap-indicator.animate {
            animation: tapRipple 0.4s ease-out;
        }

        @keyframes tapRipple {
            0% {
                opacity: 1;
                transform: scale(0);
            }

            100% {
                opacity: 0;
                transform: scale(1.5);
            }
        }

        @media (min-width: 769px) {
            .header {
                padding: 1rem 2rem;
            }

            .header h1 {
                font-size: 1.25rem;
            }

            .controls {
                bottom: 2rem;
                padding: 1rem 1.5rem;
            }

            .controls button {
                width: 3rem;
                height: 3rem;
            }

            .zoom-controls button {
                width: 2.5rem;
                height: 2.5rem;
            }
        }
    </style>
</head>

<body>
    <header class="header">
        <div style="flex: 1; min-width: 0;">
            <h1>{{ $book->title }}</h1>
            <p class="author">{{ $book->author }}</p>
        </div>
        <div class="header-buttons">
            <button class="btn" onclick="toggleFullscreen()" id="fullscreenBtn">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                    viewBox="0 0 16 16">
                    <path
                        d="M1.5 1a.5.5 0 0 0-.5.5v4a.5.5 0 0 1-1 0v-4A1.5 1.5 0 0 1 1.5 0h4a.5.5 0 0 1 0 1h-4zM10 .5a.5.5 0 0 1 .5-.5h4A1.5 1.5 0 0 1 16 1.5v4a.5.5 0 0 1-1 0v-4a.5.5 0 0 0-.5-.5h-4a.5.5 0 0 1-.5-.5zM.5 10a.5.5 0 0 1 .5.5v4a.5.5 0 0 0 .5.5h4a.5.5 0 0 1 0 1h-4A1.5 1.5 0 0 1 0 14.5v-4a.5.5 0 0 1 .5-.5zm15 0a.5.5 0 0 1 .5.5v4a1.5 1.5 0 0 1-1.5 1.5h-4a.5.5 0 0 1 0-1h4a.5.5 0 0 0 .5-.5v-4a.5.5 0 0 1 .5-.5z" />
                </svg>
                <span class="hidden md:inline">Fullscreen</span>
            </button>
            <a href="javascript:void(0);" onclick="closeReader();" class="btn">✕ Tutup</a>
        </div>
    </header>

    <div class="pdf-container" id="pdfContainer">
        <div id="loading" class="loading">
            <div class="spinner"></div>
            <p>Memuat buku...</p>
        </div>
        <div class="pdf-wrapper" id="pdfWrapper">
            <canvas id="pdfCanvas" class="hidden"></canvas>
        </div>
    </div>

    <div class="controls hidden" id="controls">
        <button id="prevBtn" onclick="prevPage()">❮</button>
        <span class="page-info" id="pageInfo">1 / 1</span>
        <button id="nextBtn" onclick="nextPage()">❯</button>
        <div class="zoom-controls">
            <button onclick="zoomOut()" title="Perkecil">−</button>
            <span class="zoom-info" id="zoomInfo">100%</span>
            <button onclick="zoomIn()" title="Perbesar">+</button>
            <button onclick="resetZoom()" title="Reset" style="font-size: 0.75rem;">↺</button>
        </div>
    </div>

    <div class="fullscreen-hint" id="fullscreenHint">Ketuk dua kali untuk tampilkan/sembunyikan kontrol</div>
    <div class="tap-indicator" id="tapIndicator"></div>

    <script>
        pdfjsLib.GlobalWorkerOptions.workerSrc = 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.worker.min.js';

        let pdfDoc = null;
        let currentPage = 1;
        let totalPages = 0;
        let rendering = false;
        let currentZoom = 1;
        let isFullscreen = false;
        const MIN_ZOOM = 0.5;
        const MAX_ZOOM = 4;

        const canvas = document.getElementById('pdfCanvas');
        const ctx = canvas.getContext('2d');
        const pdfWrapper = document.getElementById('pdfWrapper');
        const pdfContainer = document.getElementById('pdfContainer');

        // Pinch to zoom variables
        let initialDistance = 0;
        let initialZoom = 1;
        let currentTranslateX = 0;
        let currentTranslateY = 0;
        let isPinching = false;
        let isDragging = false;
        let lastTouchX = 0;
        let lastTouchY = 0;

        async function renderPage(num) {
            if (rendering) return;
            rendering = true;

            const page = await pdfDoc.getPage(num);

            // Calculate scale to fit screen
            const container = document.querySelector('.pdf-container');
            const maxWidth = container.clientWidth - 20;
            const maxHeight = container.clientHeight - 20;

            const viewport = page.getViewport({
                scale: 1
            });
            const scale = Math.min(maxWidth / viewport.width, maxHeight / viewport.height);
            const scaledViewport = page.getViewport({
                scale
            });

            // Fix blurry rendering on high-DPI mobile screens
            const pixelRatio = window.devicePixelRatio || 1;
            canvas.width = scaledViewport.width * pixelRatio;
            canvas.height = scaledViewport.height * pixelRatio;
            canvas.style.width = scaledViewport.width + 'px';
            canvas.style.height = scaledViewport.height + 'px';

            // Reset transform and scale for high-DPI
            ctx.setTransform(pixelRatio, 0, 0, pixelRatio, 0, 0);

            await page.render({
                canvasContext: ctx,
                viewport: scaledViewport
            }).promise;

            document.getElementById('pageInfo').textContent = `${num} / ${totalPages}`;
            document.getElementById('prevBtn').disabled = num <= 1;
            document.getElementById('nextBtn').disabled = num >= totalPages;

            // Reset zoom when changing pages
            resetZoom();

            rendering = false;
        }

        function prevPage() {
            if (currentPage <= 1) return;
            currentPage--;
            renderPage(currentPage);
        }

        function nextPage() {
            if (currentPage >= totalPages) return;
            currentPage++;
            renderPage(currentPage);
        }

        // Zoom functions
        function updateZoom() {
            pdfWrapper.style.transform = `translate(${currentTranslateX}px, ${currentTranslateY}px) scale(${currentZoom})`;
            document.getElementById('zoomInfo').textContent = Math.round(currentZoom * 100) + '%';
        }

        function zoomIn() {
            if (currentZoom < MAX_ZOOM) {
                currentZoom = Math.min(MAX_ZOOM, currentZoom + 0.25);
                updateZoom();
            }
        }

        function zoomOut() {
            if (currentZoom > MIN_ZOOM) {
                currentZoom = Math.max(MIN_ZOOM, currentZoom - 0.25);
                // Reset position if zooming out to 1 or below
                if (currentZoom <= 1) {
                    currentTranslateX = 0;
                    currentTranslateY = 0;
                }
                updateZoom();
            }
        }

        function resetZoom() {
            currentZoom = 1;
            currentTranslateX = 0;
            currentTranslateY = 0;
            updateZoom();
        }

        // Pinch to zoom
        function getDistance(touch1, touch2) {
            const dx = touch1.clientX - touch2.clientX;
            const dy = touch1.clientY - touch2.clientY;
            return Math.sqrt(dx * dx + dy * dy);
        }

        pdfContainer.addEventListener('touchstart', (e) => {
            if (e.touches.length === 2) {
                isPinching = true;
                initialDistance = getDistance(e.touches[0], e.touches[1]);
                initialZoom = currentZoom;
            } else if (e.touches.length === 1 && currentZoom > 1) {
                isDragging = true;
                lastTouchX = e.touches[0].clientX;
                lastTouchY = e.touches[0].clientY;
            }
        }, {
            passive: true
        });

        pdfContainer.addEventListener('touchmove', (e) => {
            if (isPinching && e.touches.length === 2) {
                const newDistance = getDistance(e.touches[0], e.touches[1]);
                const scale = newDistance / initialDistance;
                currentZoom = Math.min(MAX_ZOOM, Math.max(MIN_ZOOM, initialZoom * scale));
                updateZoom();
            } else if (isDragging && e.touches.length === 1 && currentZoom > 1) {
                const deltaX = e.touches[0].clientX - lastTouchX;
                const deltaY = e.touches[0].clientY - lastTouchY;

                // Limit panning based on zoom level
                const maxPan = (currentZoom - 1) * 150;
                currentTranslateX = Math.max(-maxPan, Math.min(maxPan, currentTranslateX + deltaX));
                currentTranslateY = Math.max(-maxPan, Math.min(maxPan, currentTranslateY + deltaY));

                updateZoom();
                lastTouchX = e.touches[0].clientX;
                lastTouchY = e.touches[0].clientY;
            }
        }, {
            passive: true
        });

        pdfContainer.addEventListener('touchend', (e) => {
            if (e.touches.length < 2) {
                isPinching = false;
            }
            if (e.touches.length === 0) {
                isDragging = false;
            }
        }, {
            passive: true
        });

        // Fullscreen functionality
        function toggleFullscreen() {
            isFullscreen = !isFullscreen;
            document.body.classList.toggle('fullscreen-mode', isFullscreen);

            if (isFullscreen) {
                // Show hint
                const hint = document.getElementById('fullscreenHint');
                hint.classList.add('show');
                setTimeout(() => hint.classList.remove('show'), 2000);

                // Try native fullscreen
                if (document.documentElement.requestFullscreen) {
                    document.documentElement.requestFullscreen().catch(() => {});
                } else if (document.documentElement.webkitRequestFullscreen) {
                    document.documentElement.webkitRequestFullscreen();
                }
            } else {
                document.body.classList.remove('show-ui');
                if (document.exitFullscreen) {
                    document.exitFullscreen().catch(() => {});
                } else if (document.webkitExitFullscreen) {
                    document.webkitExitFullscreen();
                }
            }

            // Re-render page to fit new dimensions
            setTimeout(() => renderPage(currentPage), 100);
        }

        // Double tap to show/hide UI in fullscreen
        let lastTapTime = 0;
        pdfContainer.addEventListener('click', (e) => {
            if (!isFullscreen) return;

            const now = Date.now();
            if (now - lastTapTime < 300) {
                // Double tap
                document.body.classList.toggle('show-ui');

                // Show tap indicator
                const indicator = document.getElementById('tapIndicator');
                indicator.style.left = (e.clientX - 30) + 'px';
                indicator.style.top = (e.clientY - 30) + 'px';
                indicator.classList.remove('animate');
                void indicator.offsetWidth; // Trigger reflow
                indicator.classList.add('animate');
            }
            lastTapTime = now;
        });

        // Single finger swipe for page navigation (only when not zoomed)
        let touchStartX = 0;
        let touchStartY = 0;
        canvas.addEventListener('touchstart', (e) => {
            if (e.touches.length === 1 && currentZoom <= 1) {
                touchStartX = e.touches[0].clientX;
                touchStartY = e.touches[0].clientY;
            }
        }, {
            passive: true
        });

        canvas.addEventListener('touchend', (e) => {
            if (currentZoom <= 1 && !isPinching) {
                const diffX = touchStartX - e.changedTouches[0].clientX;
                const diffY = Math.abs(touchStartY - e.changedTouches[0].clientY);

                // Only trigger if horizontal swipe is greater than vertical
                if (Math.abs(diffX) > 50 && Math.abs(diffX) > diffY) {
                    diffX > 0 ? nextPage() : prevPage();
                }
            }
        }, {
            passive: true
        });

        async function loadPDF() {
            try {
                pdfDoc = await pdfjsLib.getDocument("{{ route('book.stream', $book) }}").promise;
                totalPages = pdfDoc.numPages;

                document.getElementById('loading').classList.add('hidden');
                canvas.classList.remove('hidden');
                document.getElementById('controls').classList.remove('hidden');

                await renderPage(1);

                // Keyboard navigation
                document.addEventListener('keydown', (e) => {
                    if (e.key === 'ArrowLeft') prevPage();
                    if (e.key === 'ArrowRight') nextPage();
                    if (e.key === 'Escape' && isFullscreen) toggleFullscreen();
                    if (e.key === '+' || e.key === '=') zoomIn();
                    if (e.key === '-') zoomOut();
                    if (e.key === '0') resetZoom();
                    if (e.key === 'f' || e.key === 'F') toggleFullscreen();
                });

            } catch (error) {
                document.getElementById('loading').innerHTML =
                    '<p style="color: #ff6b6b;">Gagal memuat buku</p>';
            }
        }

        function closeReader() {
            // Exit fullscreen first
            if (isFullscreen) {
                if (document.exitFullscreen) {
                    document.exitFullscreen().catch(() => {});
                } else if (document.webkitExitFullscreen) {
                    document.webkitExitFullscreen();
                }
            }

            // Try to close the tab if opened as new tab
            window.close();
            // If window.close() doesn't work (not opened by script), go back
            setTimeout(() => {
                if (!window.closed) {
                    window.location.href = document.referrer || '/';
                }
            }, 100);
        }

        // Handle orientation change
        window.addEventListener('orientationchange', () => {
            setTimeout(() => renderPage(currentPage), 200);
        });

        // Handle resize
        window.addEventListener('resize', () => {
            if (!rendering) {
                renderPage(currentPage);
            }
        });

        loadPDF();
    </script>
</body>

</html>
