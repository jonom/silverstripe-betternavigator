<% require javascript("betternavigator/javascript/betternavigator.js") %>
<% require css("betternavigator/css/betternavigator.css") %>

<div id="BetterNavigator" class="collapsed">

    <% with $BetterNavigator %>
        <div id="BetterNavigatorStatus" class="$Viewing">
            <span class="bn-icon-cog"></span>
            $Viewing
            <span class="bn-icon-close"></span>
        </div>
    <% end_with %>

    <div id="BetterNavigatorContent">

        <div class="bn-links">

            <% with $BetterNavigator %>
                <% if $ArchiveLink.Active %>
                    <% if $EditLink %><a href="$EditLink" target="_blank"><span class="bn-icon-edit"></span>Restore</a><% end_if %>
                <% else %>
                    <% if not $LiveLink.Active %>
                        <% if $LiveLink.Link %>
                            <a href="$LiveLink.Link"><span class="bn-icon-view"></span>View live</a>
                        <% else %>
                            <span class="bn-disabled"><span class="bn-icon-view"></span>Not yet published</span>
                        <% end_if %>
                    <% end_if %>
                    <% if not $StageLink.Active %>
                        <% if $StageLink.Link %>
                            <a href="$StageLink.Link"><span class="bn-icon-view"></span>View draft</a>
                        <% else %>
                            <span class="bn-disabled"><span class="bn-icon-view"></span>Deleted from draft site</span>
                        <% end_if %>
                    <% end_if %>
                    <% if $EditLink %><a href="$EditLink" target="_blank"><span class="bn-icon-edit"></span>Edit in CMS</a><% end_if %>
                <% end_if %>

                <% if $Member %>
                    <a href="$LogoutLink"><span class="bn-icon-user"></span>Log out<% if $Member.FirstName %><span class="light"> ($Member.FirstName)</span><% end_if %></a>
                <% else %>
                    <a href="$LoginLink"><span class="bn-icon-user"></span>Log in</a>
                <% end_if %>
            <% end_with %>

        </div>

        <% include BetterNavigatorExtraContent %>

        <% if $BetterNavigator.Mode=='dev' || $BetterNavigator.IsDeveloper %>

            <div class="bn-heading">Developer tools</div>

            <div class="bn-links">

                <% if $BetterNavigator.Mode='dev' %>
                    <span class="bn-disabled" title="Log out to end Dev Mode"><span class="bn-icon-tick"></span>Dev mode on</span>
                <% else %>
                    <a href="$Link?isDev=1"><span class="bn-icon-devmode"></span>Dev mode</a>
                <% end_if %>

                <a href="$Link?flush=1" title="Flush templates and manifest, and regenerate images for this page (behaviour varies by Framework version)"><span class="bn-icon-flush"></span>Flush caches</a>
                <a href="/dev/build/?flush=1" target="_blank" title="Build database and flush caches (excludes template caches pre SS-3.1.7)"><span class="bn-icon-db"></span>Build database</a>
                <a href="/dev/" target="_blank"><span class="bn-icon-tools"></span>Dev menu</a>

            </div>

            <% include BetterNavigatorExtraDevTools %>

            <div class="bn-heading">Debugging</div>

            <div class="bn-links">

                <a href="$Link?showtemplate=1"><span class="bn-icon-info"></span>Show template</a>
                <a href="$Link?debug=1&amp;isDev=1"><span class="bn-icon-info"></span>Debug page</a>
                <a href="$Link?debugmethods=1"><span class="bn-icon-info"></span>Debug methods</a>
                <a href="$Link?debug_request=1&amp;isDev=1"><span class="bn-icon-info"></span>Debug request</a>
                <a href="$Link?debugfailover=1&amp;isDev=1"><span class="bn-icon-info"></span>Debug failover</a>
                <a href="$Link?showqueries=1&amp;isDev=1"><span class="bn-icon-info"></span>Show queries</a>
                <a href="$Link?previewwrite=1&amp;isDev=1"><span class="bn-icon-info"></span>Preview write</a>

            </div>

            <% include BetterNavigatorExtraDebugging %>

        <% end_if %>

    </div>

</div>
