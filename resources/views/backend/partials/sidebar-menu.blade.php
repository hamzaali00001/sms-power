<div class="sms_navigation">
    <ul id="responsive_menu">
        <li><a href="{{ route('dashboard') }}"><i class="fa fa-home"></i> Home</a></li>
        @if (!Auth::user()->hasRole('postpaid'))
        <li><a href="{{ route('purchases.index') }}"><i class="fa fa-shopping-cart"></i> Credit</a></li>
        @endif
        <li><a href="{{ route('groups.index') }}"><i class="fa fa-address-book-o"></i> Groups</a></li>
        @if (Auth::user()->isParentAccount())
        <li><a href="{{ route('senderids.index') }}"><i class="fa fa-asterisk"></i> Sender IDs</a></li>
        @endif
        <li><a href="{{ route('send-sms') }}"><i class="fa fa-send"></i> Send SMS</a></li>
        <li><a href="{{ route('sent-sms.index') }}"><i class="fa fa-check-square-o"></i>Sent SMS</a></li>
        <li><a href="{{ route('scheduled-sms.index') }}"><i class="fa fa-clock-o"></i>Scheduled SMS</a></li>
        <li><a href="{{ route('reports') }}"><i class="fa fa-calendar"></i>SMS Reports</a></li>
        <li><a href="{{ route('templates.index') }}"><i class="fa fa-file-text-o"></i> Templates</a></li>
        @if (Auth::user()->hasRole('admin'))
        <li><a href="{{ route('users.index') }}"><i class="fa fa-group"></i> User Accounts</a></li>
        @endif
        @if (Auth::user()->isParentAccount())
        <li><a href="{{ route('sub-accounts.index') }}"><i class="fa fa-cogs"></i> Sub Accounts</a></li>
        @endif
        <li><a href="{{ route('contact-us') }}"><i class="fa fa-envelope"></i> Contact us</a></li>
    </ul>
</div>
