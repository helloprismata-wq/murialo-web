@extends('lowongan.layout')

@section('title', 'Pengerjaan Tes: ' . ($penugasan->paket_snapshot['nama'] ?? 'Tes'))

@section('content')
    <!-- Sticky Exam Header Bar -->
    <div class="exam-bar">
        <div>
            <div style="font-size: 11px; text-transform: uppercase; color: var(--text-muted); letter-spacing: 0.5px;">Pengerjaan Tes</div>
            <h2 style="font-size: 16px; margin-top: 2px;">{{ $penugasan->paket_snapshot['nama'] ?? 'Tes Rekrutmen' }}</h2>
        </div>

        <!-- Autosave Indicator Status -->
        <div id="autosave-indicator" class="autosave-status">
            <span class="dot"></span>
            <span id="autosave-text">Semua jawaban tersimpan</span>
        </div>

        <!-- Countdown Timer -->
        <div class="exam-timer" id="exam-timer" title="Sisa Waktu Pengerjaan">
            <span>⏱</span>
            <span id="timer-display">--:--</span>
        </div>
    </div>

    <form method="post" action="{{ route('kandidat.submit', $penugasan) }}" id="exam-form" onsubmit="return confirmSubmitTest()">
        @csrf

        <div class="grid" style="display: grid; grid-template-columns: 3fr 1fr; gap: 24px;">
            <div>
                <!-- Question Cards -->
                @foreach ($soalList as $index => $s)
                    <div class="panel soal-container" id="soal-block-{{ $s['id'] }}" style="margin-bottom: 24px; {{ $index > 0 ? 'display: none;' : '' }}" data-soal-id="{{ $s['id'] }}" data-index="{{ $index + 1 }}">
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 14px; border-bottom: 1px solid var(--border); padding-bottom: 10px;">
                            <div style="display: flex; align-items: center; gap: 10px;">
                                <span style="font-size: 14px; font-weight: 700; color: var(--accent); background: var(--accent-surface); padding: 4px 10px; border-radius: var(--radius-pill);">
                                    Soal {{ $index + 1 }} dari {{ count($soalList) }}
                                </span>
                                <span style="font-size: 12px; color: var(--text-muted);">
                                    Kategori: {{ ucwords(str_replace('_', ' ', $s['kategori'])) }}
                                </span>
                            </div>
                            <span style="font-size: 12.5px; color: var(--text-muted);">
                                Bobot: <strong>{{ $s['skor_maksimum'] }} poin</strong>
                            </span>
                        </div>

                        @if (!empty($s['teks_bacaan']))
                            <div class="reading-context">
                                <h4>Informasi / Bacaan:</h4>
                                {!! nl2br(e($s['teks_bacaan'])) !!}
                            </div>
                        @endif

                        <div style="font-size: 16px; font-weight: 600; color: #f8fafc; line-height: 1.55; margin-bottom: 16px;">
                            {{ $s['pertanyaan'] }}
                        </div>

                        <div>
                            <label for="jawaban-{{ $s['id'] }}" style="font-size: 13px; font-weight: 600; color: var(--text-secondary); margin-bottom: 6px;">
                                Tuliskan Jawaban Anda:
                            </label>
                            <textarea id="jawaban-{{ $s['id'] }}"
                                      name="jawaban[{{ $s['id'] }}]"
                                      class="jawaban-textarea"
                                      data-soal-id="{{ $s['id'] }}"
                                      rows="5"
                                      placeholder="Ketik jawaban singkat Anda di sini..."
                                      style="font-size: 14.5px; line-height: 1.6;">{{ $jawabanMap[$s['id']] ?? '' }}</textarea>
                        </div>

                        <!-- Stepper navigation buttons -->
                        <div style="display: flex; justify-content: space-between; margin-top: 20px; padding-top: 14px; border-top: 1px solid var(--border);">
                            <button type="button" class="button button-secondary" onclick="navigateSoal({{ $index - 1 }})" @disabled($index === 0)>
                                ← Sebelumnya
                            </button>
                            @if ($index + 1 < count($soalList))
                                <button type="button" class="button button-secondary" onclick="navigateSoal({{ $index + 1 }})">
                                    Berikutnya →
                                </button>
                            @else
                                <button type="button" class="button button-primary" onclick="confirmSubmitTestModal()">
                                    Selesai & Kirim Tes ✓
                                </button>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>

            <div>
                <!-- Question Navigator Sidebar -->
                <div class="panel" style="position: sticky; top: 160px;">
                    <h3 style="font-size: 14px; margin-bottom: 12px; color: var(--text);">Navigasi Soal</h3>
                    
                    <div class="soal-nav-grid">
                        @foreach ($soalList as $index => $s)
                            @php
                                $hasAnswer = !empty(trim($jawabanMap[$s['id']] ?? ''));
                            @endphp
                            <button type="button"
                                    id="nav-btn-{{ $s['id'] }}"
                                    class="soal-nav-btn {{ $index === 0 ? 'active' : '' }} {{ $hasAnswer ? 'answered' : '' }}"
                                    onclick="jumpToSoal({{ $index }})"
                                    title="Soal {{ $index + 1 }}">
                                {{ $index + 1 }}
                            </button>
                        @endforeach
                    </div>

                    <div style="margin-top: 18px; padding-top: 14px; border-top: 1px solid var(--border); font-size: 12px; color: var(--text-muted); display: flex; flex-direction: column; gap: 6px;">
                        <div style="display: flex; align-items: center; gap: 8px;">
                            <span style="width: 12px; height: 12px; border-radius: 3px; background: rgba(52, 211, 153, 0.15); border: 1px solid rgba(52, 211, 153, 0.4);"></span>
                            <span>Sudah Dijawab</span>
                        </div>
                        <div style="display: flex; align-items: center; gap: 8px;">
                            <span style="width: 12px; height: 12px; border-radius: 3px; background: var(--bg-surface-alt); border: 1px solid var(--border);"></span>
                            <span>Belum Dijawab</span>
                        </div>
                    </div>

                    <div style="margin-top: 24px;">
                        <button type="submit" id="btn-submit-final" class="button button-primary" style="width: 100%;">
                            Kirim Jawaban Tes
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>

    <script>
        const soalList = @json($soalList);
        const autosaveUrl = "{{ route('kandidat.autosave', $penugasan) }}";
        const csrfToken = "{{ csrf_token() }}";
        let remainingSeconds = {{ $sisaDetik }};
        let currentIndex = 0;
        let autosaveTimeout = null;
        let isSubmitting = false;

        // ---------- Timer Implementation ----------
        const timerDisplay = document.getElementById('timer-display');
        const timerBox = document.getElementById('exam-timer');

        function updateTimer() {
            if (remainingSeconds <= 0) {
                timerDisplay.textContent = "00:00";
                timerBox.className = "exam-timer danger";
                autoFinalizeTimeUp();
                return;
            }

            const minutes = Math.floor(remainingSeconds / 60);
            const seconds = remainingSeconds % 60;
            timerDisplay.textContent = 
                String(minutes).padStart(2, '0') + ':' + String(seconds).padStart(2, '0');

            if (remainingSeconds <= 300 && remainingSeconds > 60) {
                timerBox.className = "exam-timer warning";
            } else if (remainingSeconds <= 60) {
                timerBox.className = "exam-timer danger";
            }

            remainingSeconds--;
        }

        const timerInterval = setInterval(updateTimer, 1000);
        updateTimer();

        function autoFinalizeTimeUp() {
            clearInterval(timerInterval);
            if (isSubmitting) return;
            isSubmitting = true;
            alert('Waktu pengerjaan tes telah habis! Seluruh jawaban Anda yang telah tersimpan akan otomatis dikirimkan.');
            document.getElementById('exam-form').submit();
        }

        // ---------- Navigation ----------
        function showSoal(index) {
            if (index < 0 || index >= soalList.length) return;
            currentIndex = index;

            document.querySelectorAll('.soal-container').forEach((el, i) => {
                el.style.display = (i === index) ? 'block' : 'none';
            });

            document.querySelectorAll('.soal-nav-btn').forEach((btn, i) => {
                if (i === index) {
                    btn.classList.add('active');
                } else {
                    btn.classList.remove('active');
                }
            });

            window.scrollTo({ top: 120, behavior: 'smooth' });
        }

        function navigateSoal(newIndex) {
            showSoal(newIndex);
        }

        function jumpToSoal(index) {
            showSoal(index);
        }

        // ---------- Autosave ----------
        const indicator = document.getElementById('autosave-indicator');
        const indicatorText = document.getElementById('autosave-text');

        function setAutosaveState(state, text) {
            indicator.className = 'autosave-status ' + state;
            indicatorText.textContent = text;
        }

        document.querySelectorAll('.jawaban-textarea').forEach(textarea => {
            textarea.addEventListener('input', function() {
                const soalId = this.dataset.soalId;
                const value = this.value;

                // Update sidebar button answered state
                const navBtn = document.getElementById('nav-btn-' + soalId);
                if (navBtn) {
                    if (value.trim().length > 0) {
                        navBtn.classList.add('answered');
                    } else {
                        navBtn.classList.remove('answered');
                    }
                }

                setAutosaveState('saving', 'Menyimpan...');

                clearTimeout(autosaveTimeout);
                autosaveTimeout = setTimeout(() => {
                    performAutosave(soalId, value);
                }, 1200); // Debounce 1.2s
            });
        });

        async function performAutosave(soalId, jawaban) {
            try {
                const res = await fetch(autosaveUrl, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        soal_id: soalId,
                        jawaban: jawaban
                    })
                });

                const data = await res.json();
                if (res.ok && data.success) {
                    setAutosaveState('', 'Tersimpan otomatis (' + (data.saved_at || '') + ')');
                } else {
                    setAutosaveState('error', 'Gagal menyimpan: ' + (data.message || 'Periksa koneksi'));
                }
            } catch (err) {
                setAutosaveState('error', 'Koneksi terputus. Jawaban belum tersimpan.');
            }
        }

        // ---------- Submission Confirmation ----------
        function confirmSubmitTest() {
            if (isSubmitting) return true;
            const confirmed = confirm('Apakah Anda yakin ingin mengirimkan jawaban tes sekarang?\n\nSetelah dikirim, jawaban Anda akan terkunci dan tidak dapat diubah kembali.');
            if (confirmed) {
                isSubmitting = true;
                setAutosaveState('saving', 'Memfinalisasi jawaban...');
                return true;
            }
            return false;
        }

        function confirmSubmitTestModal() {
            if (confirmSubmitTest()) {
                document.getElementById('exam-form').submit();
            }
        }
    </script>
@endsection
