@component('mail::message')
# Introduction

The body of your message.

- one
- two
- three

@component('mail::button', ['url' => ''])
Button Text
@endcomponent

@component('mail::panel', ['url' => ''])
Lorem Ipsom
@endcomponent

@component('mail::table', ['url' => ''])
#some table, google how to make a table in markdown
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
