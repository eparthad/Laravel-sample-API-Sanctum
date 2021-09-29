@component('mail::message')
# A product is reviewed by {{ $review->review->name}}

@component('mail::button', ['url' => route('review.show', $review->review->id)])
View
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
