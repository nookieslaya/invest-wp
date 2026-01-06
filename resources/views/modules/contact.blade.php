@php
  $sectionTitle = $module['section_title'] ?? '';
  $heading = $module['heading'] ?? '';
  $description = $module['description'] ?? '';
  $officeTitle = $module['office_title'] ?? '';
  $officeDescription = $module['office_description'] ?? '';
  $formTitle = $module['form_title'] ?? '';
  $formDescription = $module['form_description'] ?? '';

  $officeTitle = $officeTitle ?: __('Siedziba biura', 'sage');
  $formTitle = $formTitle ?: __('Formularz kontaktowy', 'sage');
  $formDescription = $formDescription ?: __('Prosze zostawic dane, a oddzwonimy.', 'sage');

  $officePhone = function_exists('get_field') ? (get_field('phone_number', 'option') ?: '') : '';
  $officeAddress = function_exists('get_field') ? (get_field('footer_address', 'option') ?: '') : '';
  $officeHours = function_exists('get_field') ? (get_field('footer_hours', 'option') ?: '') : '';

  $contactStatus = isset($_GET['contact']) ? sanitize_text_field(wp_unslash($_GET['contact'])) : '';
  $currentUrl = isset($_SERVER['REQUEST_URI']) ? home_url($_SERVER['REQUEST_URI']) : get_permalink();
  $formId = function_exists('wp_unique_id') ? wp_unique_id('contact-form-') : 'contact-form';
  $subject = __('Zapytanie kontaktowe', 'sage');

  $nameId = $formId.'-name';
  $phoneId = $formId.'-phone';
  $emailId = $formId.'-email';
  $messageId = $formId.'-message';
@endphp

<section class="py-16 md:py-24" id="kontakt">
  <div class="container-main space-y-10 md:space-y-12">
    <div class="max-w-3xl space-y-4">
      @if ($sectionTitle)
        <p class="section-title" data-title="{{ $sectionTitle }}">{{ $sectionTitle }}</p>
      @endif

      @if ($heading)
        <h2 class="text-3xl font-semibold text-slate-900 md:text-4xl">{{ $heading }}</h2>
      @endif

      @if ($description)
        <p class="text-base text-slate-600 md:text-lg">{{ $description }}</p>
      @endif
    </div>

    <div class="grid gap-10 lg:grid-cols-[minmax(0,0.9fr)_minmax(0,1.1fr)] lg:items-stretch">
      <div class="contact-panel h-full">
        <div class="contact-panel__content space-y-5">
          @if ($officeTitle)
            <p class="contact-panel__eyebrow">{{ $officeTitle }}</p>
          @endif

          @if ($officeDescription)
            <p class="contact-panel__text">{!! nl2br(e($officeDescription)) !!}</p>
          @endif

          @if ($officePhone)
            <p class="contact-panel__link">
              <a class="hover:text-slate-700" href="tel:{{ preg_replace('/\\s+/', '', $officePhone) }}">{{ $officePhone }}</a>
            </p>
          @endif

          @if ($officeAddress)
            <div class="contact-panel__text">{!! nl2br(e($officeAddress)) !!}</div>
          @endif

          @if ($officeHours)
            <div class="contact-panel__text">{!! nl2br(e($officeHours)) !!}</div>
          @endif
        </div>
      </div>

      <div class="contact-panel">
        <div class="contact-panel__content">
          <h3 class="contact-panel__title">{{ $formTitle }}</h3>
          @if ($formDescription)
            <p class="mt-2 contact-panel__text">{{ $formDescription }}</p>
          @endif

        @if ($contactStatus === 'sent')
          <div class="mt-4 rounded-2xl border border-emerald-200 bg-emerald-50 p-4 text-sm text-emerald-800">
            {{ __('Dziekujemy. Wiadomosc zostala wyslana.', 'sage') }}
          </div>
        @elseif ($contactStatus === 'error')
          <div class="mt-4 rounded-2xl border border-rose-200 bg-rose-50 p-4 text-sm text-rose-800">
            {{ __('Nie udalo sie wyslac formularza. Sprobuj ponownie.', 'sage') }}
          </div>
        @endif

          <form class="mt-6 grid gap-4" action="{{ esc_url(admin_url('admin-post.php')) }}" method="post">
          @php
            wp_nonce_field('investment_contact', 'investment_contact_nonce');
          @endphp
          <input type="hidden" name="action" value="investment_contact">
          <input type="hidden" name="redirect" value="{{ esc_url($currentUrl) }}">
          <input type="hidden" name="redirect_anchor" value="kontakt">
          <input type="hidden" name="form_subject" value="{{ esc_attr($subject) }}">

          <div class="grid gap-2">
            <label class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-500" for="{{ $nameId }}">{{ __('Imie i nazwisko', 'sage') }}</label>
            <input class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-900 shadow-sm focus:border-slate-900 focus:outline-none focus:ring-1 focus:ring-slate-900" type="text" id="{{ $nameId }}" name="contact_name" required>
          </div>

          <div class="grid gap-2">
            <label class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-500" for="{{ $phoneId }}">{{ __('Telefon', 'sage') }}</label>
            <input class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-900 shadow-sm focus:border-slate-900 focus:outline-none focus:ring-1 focus:ring-slate-900" type="tel" id="{{ $phoneId }}" name="contact_phone" required>
          </div>

          <div class="grid gap-2">
            <label class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-500" for="{{ $emailId }}">{{ __('Email', 'sage') }}</label>
            <input class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-900 shadow-sm focus:border-slate-900 focus:outline-none focus:ring-1 focus:ring-slate-900" type="email" id="{{ $emailId }}" name="contact_email" required>
          </div>

          <div class="grid gap-2">
            <label class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-500" for="{{ $messageId }}">{{ __('Wiadomosc', 'sage') }}</label>
            <textarea class="min-h-[140px] w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-900 shadow-sm focus:border-slate-900 focus:outline-none focus:ring-1 focus:ring-slate-900" id="{{ $messageId }}" name="contact_message" placeholder="{{ __('Prosze o kontakt.', 'sage') }}" required></textarea>
          </div>

            <button class="floor-view-button inline-flex w-full justify-center" type="submit">
              {{ __('Wyslij zapytanie', 'sage') }}
            </button>
          </form>
        </div>
      </div>
    </div>
  </div>
</section>
