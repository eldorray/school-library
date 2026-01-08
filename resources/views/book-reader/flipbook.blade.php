<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
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
            display: flex;
            flex-direction: column;
            font-family: 'Segoe UI', system-ui, sans-serif;
            color: #fff;
        }

        .header {
            padding: 0.75rem 1rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            background: rgba(0, 0, 0, 0.3);
            backdrop-filter: blur(10px);
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

        .header .close-btn {
            padding: 0.5rem 0.75rem;
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 0.5rem;
            color: #fff;
            text-decoration: none;
            font-size: 0.75rem;
        }

        .pdf-container {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 0.5rem;
            overflow: hidden;
        }

        #pdfCanvas {
            max-width: 100%;
            max-height: calc(100vh - 120px);
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.5);
            border-radius: 4px;
            background: #fff;
        }

        .controls {
            position: fixed;
            bottom: 1rem;
            left: 50%;
            transform: translateX(-50%);
            display: flex;
            align-items: center;
            gap: 1rem;
            padding: 0.75rem 1.5rem;
            background: rgba(0, 0, 0, 0.7);
            backdrop-filter: blur(10px);
            border-radius: 2rem;
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
        }

        .controls button:hover {
            background: rgba(255, 255, 255, 0.3);
        }

        .controls button:disabled {
            opacity: 0.3;
            cursor: not-allowed;
        }

        .controls .page-info {
            font-size: 0.875rem;
            min-width: 80px;
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

        @media (min-width: 769px) {
            .header {
                padding: 1rem 2rem;
            }

            .header h1 {
                font-size: 1.25rem;
            }

            .controls {
                bottom: 2rem;
                padding: 1rem 2rem;
            }

            .controls button {
                width: 3rem;
                height: 3rem;
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
        <a href="javascript:void(0);" onclick="closeReader();" class="close-btn">✕ Tutup</a>
    </header>

    <div class="pdf-container">
        <div id="loading" class="loading">
            <div class="spinner"></div>
            <p>Memuat buku...</p>
        </div>
        <canvas id="pdfCanvas" class="hidden"></canvas>
    </div>

    <div class="controls hidden" id="controls">
        <button id="prevBtn" onclick="prevPage()">❮</button>
        <span class="page-info" id="pageInfo">1 / 1</span>
        <button id="nextBtn" onclick="nextPage()">❯</button>
    </div>

    <script>
        pdfjsLib.GlobalWorkerOptions.workerSrc = 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.worker.min.js';

        let pdfDoc = null;
        let currentPage = 1;
        let totalPages = 0;
        let rendering = false;
        const canvas = document.getElementById('pdfCanvas');
        const ctx = canvas.getContext('2d');

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
                });

                // Touch swipe
                let touchStartX = 0;
                canvas.addEventListener('touchstart', (e) => {
                    touchStartX = e.touches[0].clientX;
                }, {
                    passive: true
                });

                canvas.addEventListener('touchend', (e) => {
                    const diff = touchStartX - e.changedTouches[0].clientX;
                    if (Math.abs(diff) > 50) {
                        diff > 0 ? nextPage() : prevPage();
                    }
                }, {
                    passive: true
                });

            } catch (error) {
                document.getElementById('loading').innerHTML =
                    '<p style="color: #ff6b6b;">Gagal memuat buku</p>';
            }
        }

        function closeReader() {
            // Try to close the tab if opened as new tab
            window.close();
            // If window.close() doesn't work (not opened by script), go back
            setTimeout(() => {
                if (!window.closed) {
                    window.location.href = document.referrer || '/';
                }
            }, 100);
        }

        loadPDF();
    </script>
</body>

</html>
