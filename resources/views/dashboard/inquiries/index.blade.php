@extends('layouts.app')

@section('title', __('app.dashboard.inquiries.title'))

@section('content')
@php
    $hasInquiries = $inquiries instanceof \Illuminate\Support\Collection
        ? $inquiries->isNotEmpty()
        : $inquiries->count() > 0;
@endphp
<section class="page-hero">
    <div class="container" data-inquiries-root>
        <p class="eyebrow">{{ __('app.dashboard.inquiries.eyebrow') }}</p>
        <h1>{{ __('app.dashboard.inquiries.heading') }}</h1>
        <p class="lead">{{ __('app.dashboard.inquiries.lead') }}</p>
        <div class="actions">
            <a class="btn btn-ghost" href="{{ route('dashboard.projects.index') }}">{{ __('app.dashboard.inquiries.back_projects') }}</a>
        </div>
        @if (session('status'))
            <div class="status-banner">{{ session('status') }}</div>
        @endif
        <div class="card-grid" data-inquiries-list>
            @foreach ($inquiries as $inquiry)
                <article class="card" data-inquiry-id="{{ $inquiry->id }}">
                    <div class="card-top">
                        <span class="chip">{{ $inquiry->project?->title ?? __('app.projects.index.project_fallback') }}</span>
                        <span class="card-meta">{{ optional($inquiry->created_at)->diffForHumans() }}</span>
                    </div>
                    <h3>
                        {{ $inquiry->visitor_name ?? __('app.dashboard.inquiries.visitor_fallback') }}
                        @if ($inquiry->client_id)
                            <span class="chip" style="background-color: var(--color-primary); color: white; font-size: 0.7rem; padding: 0.1rem 0.4rem; margin-left: 0.5rem;">{{ __('app.dashboard.inquiries.client_label') }}</span>
                        @endif
                    </h3>
                    @if ($inquiry->visitor_email)
                        <p>{{ $inquiry->visitor_email }}</p>
                    @endif

                    <div class="card" style="margin-top: 0.75rem; background: var(--surface-muted); border-style: dashed;">
                        <p style="margin: 0; font-weight: 600;">Initial Message</p>
                        <p style="margin: 0.35rem 0 0;">{{ $inquiry->message }}</p>
                    </div>

                    @if ($inquiry->replies->isNotEmpty())
                        <div style="margin-top: 0.9rem; display: grid; gap: 0.6rem;">
                            @foreach ($inquiry->replies as $reply)
                                @php
                                    $isDesignerReply = (int) optional($reply->user)->id === (int) auth()->id();
                                @endphp
                                <div class="card" style="padding: 0.85rem; background: {{ $isDesignerReply ? 'var(--surface-muted)' : 'var(--surface)' }};">
                                    <div class="card-top" style="margin-bottom: 0.35rem;">
                                        <span class="chip" style="background: {{ $isDesignerReply ? 'var(--color-primary)' : 'var(--line)' }}; color: {{ $isDesignerReply ? 'white' : 'inherit' }};">
                                            {{ $isDesignerReply ? 'Designer reply' : 'Client reply' }}
                                        </span>
                                        <span class="card-meta">{{ optional($reply->created_at)->diffForHumans() }}</span>
                                    </div>
                                    <p style="margin: 0;">{{ $reply->message }}</p>
                                </div>
                            @endforeach
                        </div>
                    @endif

                    <form class="form-row" style="margin-top: 0.9rem; display: grid; gap: 0.6rem;" method="post" action="{{ route('dashboard.inquiries.reply', $inquiry) }}">
                        @csrf
                        <label class="field" style="margin: 0;">
                            <span style="font-weight: 600;">Send reply</span>
                            <textarea name="message" rows="3" maxlength="2000" placeholder="Type your reply for the client..."></textarea>
                        </label>
                        @error('message')
                            <span class="form-error">{{ $message }}</span>
                        @enderror
                        <button class="btn btn-primary" type="submit">Send reply</button>
                    </form>
                </article>
            @endforeach
        </div>
        @if (!$hasInquiries)
            <div class="placeholder-card" data-inquiries-empty>{{ __('app.dashboard.inquiries.empty') }}</div>
        @endif
        @if (method_exists($inquiries, 'links'))
            <div class="pagination">{{ $inquiries->links('partials.pagination') }}</div>
        @endif
    </div>
</section>
@endsection
