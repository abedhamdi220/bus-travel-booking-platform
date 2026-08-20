<x-mail::message>
# Introduction

The body of your message.
<h1>there are company for rigester </h1>{{-- رسالة الى الادمن لإعلامه بشركة جديدة --}}
<x-mail::button :url="''">
Button Text
</x-mail::button>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
