@extends('app')
@section('title')
    {{ __('app.edit_content') }} | {{ __('app.app_name') }}
@endsection

@section('content')
    <div class="flex flex-col md:flex-row md:items-center justify-between mb-6 md:mb-8 gap-4">
        <div class="min-w-0">
            <nav class="flex gap-2 text-label-sm font-label-sm text-on-surface-variant mb-2 overflow-x-auto scrollbar-hide">
                <a class="hover:text-primary-fixed whitespace-nowrap" href="{{ route('admin.content.index') }}">{{ __('app.materials') }}</a>
                <span class="whitespace-nowrap">/</span>
                <a class="hover:text-primary-fixed whitespace-nowrap truncate max-w-[120px] sm:max-w-[200px]" href="{{ route('admin.content.show', $content) }}">{{ $content->title }}</a>
                <span class="whitespace-nowrap">/</span>
                <span class="text-primary-fixed whitespace-nowrap">{{ __('app.edit') }}</span>
            </nav>
            <h1 class="font-display-lg text-display-lg-mobile md:text-display-lg text-on-surface tracking-tight break-words">{{ __('app.edit_content') }}</h1>
        </div>
    </div>

    <form method="POST" action="{{ route('admin.content.update', $content) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-12 gap-gutter">
            <div class="col-span-12 xl:col-span-8 space-y-6 md:space-y-8">
                <section class="glass-card rounded-2xl p-4 md:p-8">
                    <div class="space-y-6 md:space-y-8">
                        <div class="relative">
                            <label class="block font-label-sm text-label-sm text-primary-fixed mb-2 uppercase tracking-widest">{{ __('app.material_title') }}</label>
                            <input name="title" value="{{ old('title', $content->title) }}"
                                class="neon-input w-full font-display-lg text-headline-md text-on-surface py-4 focus:placeholder-transparent @error('title') border-error @enderror"
                                placeholder="{{ __('app.title_placeholder') }}" type="text" />
                            @error('title')
                                <p class="text-error text-label-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="relative">
                            <label class="block font-label-sm text-label-sm text-primary-fixed mb-2 uppercase tracking-widest">{{ __('app.description') }}</label>
                            <textarea name="description" rows="3"
                                class="neon-input w-full font-body-md text-on-surface py-3 @error('description') border-error @enderror"
                                placeholder="{{ __('app.description_placeholder') }}">{{ old('description', $content->description) }}</textarea>
                            @error('description')
                                <p class="text-error text-label-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 md:gap-8">
                            <div class="space-y-2">
                                <label class="block font-label-sm text-label-sm text-primary-fixed uppercase tracking-widest">{{ __('app.sdg_alignment') }}</label>
                                    <select name="sdg_category"
                                        class="w-full bg-surface-container-high border-none border-b border-outline-variant/30 text-on-surface font-body-md py-3 px-4 rounded-t-lg focus:ring-0 focus:border-primary-container transition-all">
                                        <option value="">{{ __('app.select_primary_goal') }}</option>
                                        @foreach (config('sdg.categories') as $catKey => $category)
                                            <optgroup label="{{ __("app.category_{$catKey}") }}">
                                                @foreach ($category['sdgs'] as $num)
                                                    <option value="SDG {{ $num }}" @selected(old('sdg_category', $content->sdg_category) === 'SDG '.$num)>{{ __("app.sdg_{$num}") }}</option>
                                                @endforeach
                                            </optgroup>
                                        @endforeach
                                    </select>
                            </div>
                            <div class="space-y-2">
                                <label class="block font-label-sm text-label-sm text-primary-fixed uppercase tracking-widest">{{ __('app.target_keywords') }}</label>
                                <input name="tags" value="{{ old('tags', $content->tags) }}"
                                    class="neon-input w-full font-body-md py-3" placeholder="{{ __('app.keywords_placeholder') }}"
                                    type="text" />
                            </div>
                        </div>
                    </div>
                </section>

                <section class="glass-card rounded-2xl overflow-hidden flex flex-col h-[300px] md:h-[500px]">
                    <div
                        class="bg-surface-container-high/50 border-b border-outline-variant/20 p-3 md:p-4 flex flex-wrap items-center justify-between gap-2">
                        <div class="flex items-center gap-1 md:gap-2 overflow-x-auto scrollbar-hide">
                            <button type="button" onclick="exec('bold')"
                                class="editor-btn p-1.5 md:p-2 text-on-surface-variant hover:text-primary-fixed hover:bg-surface-variant/30 rounded transition-all"><span
                                    class="material-symbols-outlined text-[18px] md:text-[24px]">format_bold</span></button>
                            <button type="button" onclick="exec('italic')"
                                class="editor-btn p-1.5 md:p-2 text-on-surface-variant hover:text-primary-fixed hover:bg-surface-variant/30 rounded transition-all"><span
                                    class="material-symbols-outlined text-[18px] md:text-[24px]">format_italic</span></button>
                            <button type="button" onclick="exec('insertUnorderedList')"
                                class="editor-btn p-1.5 md:p-2 text-on-surface-variant hover:text-primary-fixed hover:bg-surface-variant/30 rounded transition-all"><span
                                    class="material-symbols-outlined text-[18px] md:text-[24px]">format_list_bulleted</span></button>
                            <span class="w-[1px] h-5 md:h-6 bg-outline-variant/30 mx-1 md:mx-2"></span>
                            <button type="button" onclick="execLink()"
                                class="editor-btn p-1.5 md:p-2 text-on-surface-variant hover:text-primary-fixed hover:bg-surface-variant/30 rounded transition-all"><span
                                    class="material-symbols-outlined text-[18px] md:text-[24px]">link</span></button>
                            <button type="button" onclick="execImage()"
                                class="editor-btn p-1.5 md:p-2 text-on-surface-variant hover:text-primary-fixed hover:bg-surface-variant/30 rounded transition-all"><span
                                    class="material-symbols-outlined text-[18px] md:text-[24px]">image</span></button>
                            <button type="button" onclick="execCode()"
                                class="editor-btn p-1.5 md:p-2 text-on-surface-variant hover:text-primary-fixed hover:bg-surface-variant/30 rounded transition-all"><span
                                    class="material-symbols-outlined text-[18px] md:text-[24px]">code</span></button>
                        </div>
                        <div class="text-[10px] md:text-label-sm font-label-sm text-on-surface-variant/50 whitespace-nowrap">{{ __('app.ai_assistance_active') }}</div>
                    </div>
                    <div class="flex-1 p-4 md:p-8 overflow-y-auto" id="editor-wrapper">
                        <div name="body-editor" contenteditable="true"
                            class="outline-none min-h-full w-full font-body-md md:font-body-lg text-on-surface/80 leading-relaxed break-words @error('body') border-error @enderror"
                            id="body-editor">{!! old('body', $content->body_html) !!}</div>
                        <input type="hidden" name="body" id="body-hidden" value="{{ old('body', $content->body) }}" />
                        @error('body')
                            <p class="text-error text-label-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </section>

                <section id="flashcards-section" class="glass-card rounded-2xl p-4 md:p-8">
                    <div class="flex items-center justify-between mb-4">
                        <div>
                            <h3 class="font-headline-md text-headline-md text-on-surface">{{ __('app.flashcards') }}</h3>
                            <p class="text-label-sm text-on-surface-variant mt-1">{{ __('app.flashcards_desc') }}</p>
                        </div>
                        <button type="button" onclick="addFlashcard()"
                            class="px-4 py-2 bg-primary-container/10 border border-primary-container/30 text-primary-fixed rounded-full hover:bg-primary-container/20 transition-all text-sm font-medium flex items-center gap-1">
                            <span class="material-symbols-outlined text-[18px]">add</span>
                            {{ __('app.add_flashcard') }}
                        </button>
                    </div>
                    <div id="flashcards-container" class="space-y-4">
                        @php $fcIndex = 0; @endphp
                        @if (old('flashcards'))
                            @foreach (old('flashcards') as $i => $fc)
                                <div class="flashcard-item glass-card-static rounded-xl p-4 space-y-3 relative">
                                    <input type="hidden" name="flashcards[{{ $i }}][exists]" value="1" />
                                    <div>
                                        <label class="block font-label-sm text-label-sm text-on-surface-variant mb-1">{{ __('app.flashcard_question') }}</label>
                                        <input name="flashcards[{{ $i }}][question]" value="{{ $fc['question'] }}"
                                            class="neon-input w-full font-body-md py-2 text-sm" placeholder="{{ __('app.flashcard_question_placeholder') }}" />
                                    </div>
                                    <div>
                                        <label class="block font-label-sm text-label-sm text-on-surface-variant mb-1">{{ __('app.flashcard_answer') }}</label>
                                        <textarea name="flashcards[{{ $i }}][answer]" rows="2"
                                            class="neon-input w-full font-body-md py-2 text-sm resize-none" placeholder="{{ __('app.flashcard_answer_placeholder') }}">{{ $fc['answer'] }}</textarea>
                                    </div>
                                    <button type="button" onclick="this.closest('.flashcard-item').remove()"
                                        class="absolute top-2 right-2 p-1 text-error hover:bg-error/10 rounded-full transition-all">
                                        <span class="material-symbols-outlined text-[18px]">close</span>
                                    </button>
                                </div>
                                @php $fcIndex = $i + 1; @endphp
                            @endforeach
                        @elseif ($content->flashcards->count())
                            @foreach ($content->flashcards as $fc)
                                @php $i = $fcIndex++; @endphp
                                <div class="flashcard-item glass-card-static rounded-xl p-4 space-y-3 relative">
                                    <input type="hidden" name="flashcards[{{ $i }}][exists]" value="1" />
                                    <div>
                                        <label class="block font-label-sm text-label-sm text-on-surface-variant mb-1">{{ __('app.flashcard_question') }}</label>
                                        <input name="flashcards[{{ $i }}][question]" value="{{ $fc->question }}"
                                            class="neon-input w-full font-body-md py-2 text-sm" placeholder="{{ __('app.flashcard_question_placeholder') }}" />
                                    </div>
                                    <div>
                                        <label class="block font-label-sm text-label-sm text-on-surface-variant mb-1">{{ __('app.flashcard_answer') }}</label>
                                        <textarea name="flashcards[{{ $i }}][answer]" rows="2"
                                            class="neon-input w-full font-body-md py-2 text-sm resize-none" placeholder="{{ __('app.flashcard_answer_placeholder') }}">{{ $fc->answer }}</textarea>
                                    </div>
                                    <button type="button" onclick="this.closest('.flashcard-item').remove()"
                                        class="absolute top-2 right-2 p-1 text-error hover:bg-error/10 rounded-full transition-all">
                                        <span class="material-symbols-outlined text-[18px]">close</span>
                                    </button>
                                </div>
                            @endforeach
                        @endif
                    </div>
                    <p id="no-flashcards-msg" class="text-label-sm text-on-surface-variant/60 text-center py-4 {{ old('flashcards') || $content->flashcards->count() ? 'hidden' : '' }}">{{ __('app.no_flashcards') }}</p>
                </section>

                <section id="quizzes-section" class="glass-card rounded-2xl p-4 md:p-8">
                    <div class="flex items-center justify-between mb-4">
                        <div>
                            <h3 class="font-headline-md text-headline-md text-on-surface">{{ __('app.quizzes') }}</h3>
                            <p class="text-label-sm text-on-surface-variant mt-1">{{ __('app.quizzes_desc') }}</p>
                        </div>
                        <button type="button" onclick="addQuiz()"
                            class="px-4 py-2 bg-primary-container/10 border border-primary-container/30 text-primary-fixed rounded-full hover:bg-primary-container/20 transition-all text-sm font-medium flex items-center gap-1">
                            <span class="material-symbols-outlined text-[18px]">add</span>
                            {{ __('app.add_quiz') }}
                        </button>
                    </div>
                    <div id="quizzes-container" class="space-y-6">
                        @php $qzIndex = 0; @endphp
                        @if (old('quizzes'))
                            @foreach (old('quizzes') as $i => $qz)
                                <div class="quiz-item glass-card-static rounded-xl p-4 space-y-3 relative">
                                    <input type="hidden" name="quizzes[{{ $i }}][exists]" value="1" />
                                    <div>
                                        <label class="block font-label-sm text-label-sm text-on-surface-variant mb-1">{{ __('app.quiz_question') }}</label>
                                        <input name="quizzes[{{ $i }}][question]" value="{{ $qz['question'] }}"
                                            class="neon-input w-full font-body-md py-2 text-sm" placeholder="{{ __('app.quiz_question_placeholder') }}" />
                                    </div>
                                    <div>
                                        <label class="block font-label-sm text-label-sm text-on-surface-variant mb-2">{{ __('app.quiz_options') }}</label>
                                        @foreach (range(0, 3) as $j)
                                            <div class="flex items-center gap-2 mb-2">
                                                <input name="quizzes[{{ $i }}][options][]" value="{{ $qz['options'][$j] ?? '' }}"
                                                    class="neon-input w-full font-body-md py-2 text-sm flex-1" placeholder="{{ __('app.quiz_option_placeholder', ['number' => $j + 1]) }}" />
                                                <input type="radio" name="quizzes[{{ $i }}][correct_answer]"
                                                    value="{{ $j }}" @checked(old('quizzes.' . $i . '.correct_answer', '0') == $j) />
                                            </div>
                                        @endforeach
                                    </div>
                                    <p class="text-[10px] text-on-surface-variant/50">{{ __('app.quiz_options_hint') }}</p>
                                    <button type="button" onclick="this.closest('.quiz-item').remove()"
                                        class="absolute top-2 right-2 p-1 text-error hover:bg-error/10 rounded-full transition-all">
                                        <span class="material-symbols-outlined text-[18px]">close</span>
                                    </button>
                                </div>
                                @php $qzIndex = $i + 1; @endphp
                            @endforeach
                        @elseif ($content->quizzes->count())
                            @foreach ($content->quizzes as $qz)
                                @php $i = $qzIndex++; @endphp
                                <div class="quiz-item glass-card-static rounded-xl p-4 space-y-3 relative">
                                    <input type="hidden" name="quizzes[{{ $i }}][exists]" value="1" />
                                    <div>
                                        <label class="block font-label-sm text-label-sm text-on-surface-variant mb-1">{{ __('app.quiz_question') }}</label>
                                        <input name="quizzes[{{ $i }}][question]" value="{{ $qz->question }}"
                                            class="neon-input w-full font-body-md py-2 text-sm" placeholder="{{ __('app.quiz_question_placeholder') }}" />
                                    </div>
                                    <div>
                                        <label class="block font-label-sm text-label-sm text-on-surface-variant mb-2">{{ __('app.quiz_options') }}</label>
                                        @foreach (range(0, 3) as $j)
                                            <div class="flex items-center gap-2 mb-2">
                                                <input name="quizzes[{{ $i }}][options][]" value="{{ $qz->options[$j] ?? '' }}"
                                                    class="neon-input w-full font-body-md py-2 text-sm flex-1" placeholder="{{ __('app.quiz_option_placeholder', ['number' => $j + 1]) }}" />
                                                <input type="radio" name="quizzes[{{ $i }}][correct_answer]"
                                                    value="{{ $j }}" @checked($qz->correct_answer == $j) />
                                            </div>
                                        @endforeach
                                    </div>
                                    <p class="text-[10px] text-on-surface-variant/50">{{ __('app.quiz_options_hint') }}</p>
                                    <button type="button" onclick="this.closest('.quiz-item').remove()"
                                        class="absolute top-2 right-2 p-1 text-error hover:bg-error/10 rounded-full transition-all">
                                        <span class="material-symbols-outlined text-[18px]">close</span>
                                    </button>
                                </div>
                            @endforeach
                        @endif
                    </div>
                    <p id="no-quizzes-msg" class="text-label-sm text-on-surface-variant/60 text-center py-4 {{ old('quizzes') || $content->quizzes->count() ? 'hidden' : '' }}">{{ __('app.no_quizzes') }}</p>
                </section>
            </div>

            <div class="col-span-12 xl:col-span-4 space-y-6 md:space-y-8">
                <section class="glass-card rounded-2xl p-4 md:p-6">
                    <label class="block font-label-sm text-label-sm text-primary-fixed mb-4 uppercase tracking-widest">{{ __('app.cover_media') }}</label>
                    @if ($content->thumbnail)
                        <div class="mb-4">
                            <img src="{{ Storage::url($content->thumbnail) }}"
                                class="w-full rounded-xl object-cover max-h-48" />
                        </div>
                    @endif
                    <input type="file" name="thumbnail" accept="image/jpeg,image/png,image/webp"
                        class="block w-full text-sm text-on-surface file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-primary-container file:text-on-primary-container hover:file:bg-primary-container/80 cursor-pointer" />
                    <p class="text-label-sm text-on-surface-variant mt-2">{{ __('app.recommended_thumbnail') }}</p>
                    @error('thumbnail')
                        <p class="text-error text-label-sm mt-1">{{ $message }}</p>
                    @enderror
                </section>

                <section class="glass-card-static rounded-2xl p-4 md:p-6 space-y-6">
                    <h3 class="font-headline-md text-headline-md text-on-surface">{{ __('app.settings') }}</h3>
                    <div class="space-y-4">
                        <div class="flex items-center justify-between">
                            <span class="text-body-md text-on-surface-variant">{{ __('app.status') }}</span>
                            <select name="status"
                                class="bg-surface-container-high border-none rounded-lg py-2 px-4 font-body-md text-on-surface">
                                <option value="draft" @selected(old('status', $content->status) === 'draft')>{{ __('app.draft') }}</option>
                                <option value="published" @selected(old('status', $content->status) === 'published')>{{ __('app.published') }}</option>
                            </select>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-body-md text-on-surface-variant">{{ __('app.enable_ai_summary') }}</span>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" name="ai_summary" value="1" class="sr-only peer"
                                    @checked(old('ai_summary', $content->ai_summary)) />
                                <div
                                    class="w-10 h-5 bg-surface-variant rounded-full peer peer-checked:bg-primary-container peer-checked:shadow-[0_0_10px_rgba(0,242,255,0.3)] after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:after:translate-x-5">
                                </div>
                            </label>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-body-md text-on-surface-variant">{{ __('app.public_accessibility') }}</span>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" name="public_access" value="1" class="sr-only peer"
                                    @checked(old('public_access', $content->public_access)) />
                                <div
                                    class="w-10 h-5 bg-surface-variant rounded-full peer peer-checked:bg-primary-container peer-checked:shadow-[0_0_10px_rgba(0,242,255,0.3)] after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:after:translate-x-5">
                                </div>
                            </label>
                        </div>
                    </div>

                    <div class="pt-6 border-t border-outline-variant/20 space-y-4">
                        <div>
                            <label
                                class="block font-label-sm text-label-sm text-on-surface-variant mb-2 uppercase tracking-tighter">{{ __('app.content_difficulty') }}</label>
                            <div class="grid grid-cols-3 gap-2">
                                <label
                                    class="py-2 text-[10px] font-bold border border-outline-variant/30 rounded hover:border-primary-container transition-all text-center cursor-pointer has-[:checked]:border-primary-container has-[:checked]:bg-primary-container/10 has-[:checked]:text-primary-container">
                                    <input type="radio" name="difficulty" value="basic" class="sr-only"
                                        @checked(old('difficulty', $content->difficulty) === 'basic') />
                                    {{ __('app.basic') }}
                                </label>
                                <label
                                    class="py-2 text-[10px] font-bold border border-outline-variant/30 rounded hover:border-primary-container transition-all text-center cursor-pointer has-[:checked]:border-primary-container has-[:checked]:bg-primary-container/10 has-[:checked]:text-primary-container">
                                    <input type="radio" name="difficulty" value="core" class="sr-only"
                                        @checked(old('difficulty', $content->difficulty) === 'core') />
                                    {{ __('app.core') }}
                                </label>
                                <label
                                    class="py-2 text-[10px] font-bold border border-outline-variant/30 rounded hover:border-primary-container transition-all text-center cursor-pointer has-[:checked]:border-primary-container has-[:checked]:bg-primary-container/10 has-[:checked]:text-primary-container">
                                    <input type="radio" name="difficulty" value="expert" class="sr-only"
                                        @checked(old('difficulty', $content->difficulty) === 'expert') />
                                    {{ __('app.expert') }}
                                </label>
                            </div>
                        </div>
                    </div>
                </section>

                <section
                    class="glass-card-static rounded-2xl p-4 md:p-6 bg-gradient-to-br from-surface-container-high/50 to-primary-container/5 border border-primary-container/20">
                    <div class="flex items-center gap-2 mb-4">
                        <span class="material-symbols-outlined text-primary-container"
                            style="font-variation-settings: 'FILL' 1;">auto_awesome</span>
                        <h4 class="font-headline-md text-headline-md text-primary-fixed">{{ __('app.nexus_ai_assistant') }}</h4>
                    </div>
                    <div class="space-y-4">
                        <p class="text-label-sm leading-relaxed text-on-surface-variant">{{ __('app.ai_description') }}</p>
                    </div>
                </section>

                <div class="flex flex-col-reverse sm:flex-row items-stretch sm:items-center gap-3 sm:gap-4">
                    <a href="{{ route('admin.content.index') }}"
                        class="sm:flex-1 px-6 py-3 border border-primary-container/30 text-primary-fixed rounded-full hover:bg-primary-container/10 transition-all font-medium text-center">{{ __('app.cancel') }}</a>
                    <button type="submit"
                        class="sm:flex-1 px-8 py-3 bg-primary-container text-on-primary-container rounded-full font-bold shadow-[0_0_15px_rgba(0,242,255,0.3)] hover:shadow-[0_0_25px_rgba(0,242,255,0.5)] transition-all flex items-center justify-center gap-2">
                        <span class="material-symbols-outlined">save</span>
                        {{ __('app.update_material') }}
                    </button>
                </div>
            </div>
        </div>
    </form>
@endsection

@push('scripts')
<script>
    function exec(command) {
        document.getElementById('body-editor').focus();
        document.execCommand(command, false, null);
    }

    function execLink() {
        var url = prompt('{{ __('app.enter_url') }}');
        if (url) {
            document.getElementById('body-editor').focus();
            document.execCommand('createLink', false, url);
        }
    }

    function execImage() {
        var url = prompt('{{ __('app.enter_image_url') }}');
        if (url) {
            document.getElementById('body-editor').focus();
            document.execCommand('insertImage', false, url);
        }
    }

    function execCode() {
        var sel = window.getSelection();
        if (!sel.rangeCount) return;
        var range = sel.getRangeAt(0);
        var selected = range.toString();
        if (selected) {
            document.getElementById('body-editor').focus();
            var code = '<code style="background:rgba(255,255,255,0.08);padding:2px 6px;border-radius:4px;font-family:monospace;">' + selected.replace(/</g, '&lt;') + '</code>';
            document.execCommand('insertHTML', false, code);
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        var form = document.querySelector('form[action*="content"]');
        if (form) {
            form.addEventListener('submit', function() {
                var editor = document.getElementById('body-editor');
                var hidden = document.getElementById('body-hidden');
                if (editor && hidden) {
                    var html = editor.innerHTML;
                    hidden.value = html === '<br>' ? '' : html;
                }
            });
        }
    });

    let fcIndex = {{ max(old('flashcards') ? count(old('flashcards')) : 0, $content->flashcards->count()) }};
    let qzIndex = {{ max(old('quizzes') ? count(old('quizzes')) : 0, $content->quizzes->count()) }};

    function addFlashcard() {
        const container = document.getElementById('flashcards-container');
        const msg = document.getElementById('no-flashcards-msg');
        const i = fcIndex++;
        const div = document.createElement('div');
        div.className = 'flashcard-item glass-card-static rounded-xl p-4 space-y-3 relative';
        div.innerHTML = `
            <input type="hidden" name="flashcards[${i}][exists]" value="1" />
            <div>
                <label class="block font-label-sm text-label-sm text-on-surface-variant mb-1">{{ __('app.flashcard_question') }}</label>
                <input name="flashcards[${i}][question]"
                    class="neon-input w-full font-body-md py-2 text-sm" placeholder="{{ __('app.flashcard_question_placeholder') }}" />
            </div>
            <div>
                <label class="block font-label-sm text-label-sm text-on-surface-variant mb-1">{{ __('app.flashcard_answer') }}</label>
                <textarea name="flashcards[${i}][answer]" rows="2"
                    class="neon-input w-full font-body-md py-2 text-sm resize-none" placeholder="{{ __('app.flashcard_answer_placeholder') }}"></textarea>
            </div>
            <button type="button" onclick="this.closest('.flashcard-item').remove()"
                class="absolute top-2 right-2 p-1 text-error hover:bg-error/10 rounded-full transition-all">
                <span class="material-symbols-outlined text-[18px]">close</span>
            </button>`;
        container.appendChild(div);
        msg.classList.add('hidden');
    }

    const quizOptionPlaceholder = @json(__('app.quiz_option_placeholder', ['number' => '__NUM__']));

    function addQuiz() {
        const container = document.getElementById('quizzes-container');
        const msg = document.getElementById('no-quizzes-msg');
        const i = qzIndex++;
        const div = document.createElement('div');
        div.className = 'quiz-item glass-card-static rounded-xl p-4 space-y-3 relative';
        let optionsHtml = '';
        for (let j = 0; j < 4; j++) {
            optionsHtml += `
                <div class="flex items-center gap-2 mb-2">
                    <input name="quizzes[${i}][options][]"
                        class="neon-input w-full font-body-md py-2 text-sm flex-1" placeholder="${quizOptionPlaceholder.replace('__NUM__', j + 1)}" />
                    <input type="radio" name="quizzes[${i}][correct_answer]" value="${j}" />
                </div>`;
        }
        div.innerHTML = `
            <input type="hidden" name="quizzes[${i}][exists]" value="1" />
            <div>
                <label class="block font-label-sm text-label-sm text-on-surface-variant mb-1">{{ __('app.quiz_question') }}</label>
                <input name="quizzes[${i}][question]"
                    class="neon-input w-full font-body-md py-2 text-sm" placeholder="{{ __('app.quiz_question_placeholder') }}" />
            </div>
            <div>
                <label class="block font-label-sm text-label-sm text-on-surface-variant mb-2">{{ __('app.quiz_options') }}</label>
                ${optionsHtml}
            </div>
            <p class="text-[10px] text-on-surface-variant/50">{{ __('app.quiz_options_hint') }}</p>
            <button type="button" onclick="this.closest('.quiz-item').remove()"
                class="absolute top-2 right-2 p-1 text-error hover:bg-error/10 rounded-full transition-all">
                <span class="material-symbols-outlined text-[18px]">close</span>
            </button>`;
        container.appendChild(div);
        msg.classList.add('hidden');
    }
</script>
@endpush
