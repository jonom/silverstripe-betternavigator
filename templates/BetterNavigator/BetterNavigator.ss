<div id="BetterNavigator" class="better-navigator collapsed">

    <div id="BetterNavigatorStatus" class="better-navigator__status $Viewing">
        <span class="bn-icon-cog"></span>
        $ViewingTitle
        <span class="bn-icon-close"></span>
    </div>

    <div id="BetterNavigatorContent" class="better-navigator__content">

        <div class="bn-links">

            <% if $ArchiveLink.Active %>
                <% if $EditLink %><a href="$EditLink" target="_blank"><span class="bn-icon-edit"></span><%t JonoM\BetterNavigator.RESTORE_LABEL 'Restore' %></a><% end_if %>
            <% else %>
                <% if not $LiveLink.Active %>
                    <% if $LiveLink.Link %>
                        <a href="$LiveLink.Link"><span class="bn-icon-view"></span><%t JonoM\BetterNavigator.VIEW_LIVE_LABEL 'View live' %></a>
                    <% else %>
                        <span class="bn-disabled"><span class="bn-icon-view"></span><%t JonoM\BetterNavigator.NOT_YET_PUBLISHED_LABEL 'Not yet published' %></span>
                    <% end_if %>
                <% end_if %>
                <% if not $StageLink.Active %>
                    <% if $StageLink.Link %>
                        <a href="$StageLink.Link"><span class="bn-icon-view"></span><%t JonoM\BetterNavigator.VIEW_DRAFT_LABEL 'View draft' %></a>
                    <% else %>
                        <span class="bn-disabled"><span class="bn-icon-view"></span><%t JonoM\BetterNavigator.DELETED_FROM_DRAFT_SITE_LABEL 'Deleted from draft site' %></span>
                    <% end_if %>
                <% end_if %>
                <% if $EditLink %><a href="$EditLink" target="_blank"><span class="bn-icon-edit"></span><%t JonoM\BetterNavigator.EDIT_IN_CMS_LABEL 'Edit in CMS' %></a><% end_if %>
            <% end_if %>

            <% if $Member %>
                $LogoutForm
            <a href="$LogoutLink" id="BetterNavigatorLogoutLink" class="better-navigator__logout-link"><span class="bn-icon-user"></span><%t JonoM\BetterNavigator.LOG_OUT_LABEL 'Log out' %><% if $Member.FirstName %><span class="light"> ($Member.FirstName)</span><% end_if %></a>
            <% else %>
                <a href="$LoginLink"><span class="bn-icon-user"></span><%t JonoM\BetterNavigator.LOG_IN_LABEL 'Log in' %></a>
            <% end_if %>

        </div>

        <% include BetterNavigator\BetterNavigatorExtraContent %>

        <% if $Mode == 'dev' || $IsDeveloper %>

            <div class="bn-heading"><%t JonoM\BetterNavigator.DEVELOPER_TOOLS_HEADING 'Developer tools' %></div>

            <div class="bn-links">

                <% if $Mode='dev' %>
                    <span class="bn-disabled" title="<%t JonoM\BetterNavigator.END_DEV_MODE_TITLE 'Log out to end Dev Mode' %>">
                        <span class="bn-icon-tick"></span>
                        <%t JonoM\BetterNavigator.DEV_MOVE_ON_LABEL 'Dev mode on' %>
                    </span>
                <% else %>
                    <a href="{$AbsoluteLink}?isDev=1"><span class="bn-icon-devmode"></span><%t JonoM\BetterNavigator.DEV_MODE_LABEL 'Dev mode' %></a>
                <% end_if %>

                <a href="{$AbsoluteLink}?flush=1"
                   title="<%t JonoM\BetterNavigator.FLUSH_CACHE_TITLE 'Flush templates and manifest, and regenerate images for this page (behaviour varies by Framework version)' %>"
                >
                    <span class="bn-icon-flush"></span>
                    <%t JonoM\BetterNavigator.FLUSH_CACHE_LABEL 'Flush caches' %>
                </a>
                <a href="{$AbsoluteBaseURL}dev/build/?flush=1"
                   target="_blank"
                   title="<%t JonoM\BetterNavigator.BUILD_DATABASE_TITLE 'Build database and flush caches (excludes template caches pre SS-3.1.7)' %>"
                >
                    <span class="bn-icon-db"></span>
                    <%t JonoM\BetterNavigator.BUILD_DATABASE_LABEL 'Build database' %>
                </a>
                <a href="{$AbsoluteBaseURL}dev/" target="_blank"><span class="bn-icon-tools"></span><%t JonoM\BetterNavigator.DEV_MENU_LABEL 'Dev menu' %></a>

            </div>

            <% include BetterNavigator\BetterNavigatorExtraDevTools %>

        <% end_if %>

        <% if $Mode == 'dev' %>

            <div class="bn-heading"><%t JonoM\BetterNavigator.DEBUGGING_HEADING 'Debugging' %></div>

            <div class="bn-links">

                <a href="{$AbsoluteLink}?showtemplate=1"
                   title="<%t JonoM\BetterNavigator.SHOW_TEMPLATE_TITLE 'Show the compiled version of all the templates used, including line numbers. Good when you have a syntax error in a template. Cannot be used on a Live site without isDev' %>"
                >
                    <span class="bn-icon-info"></span>
                    <%t JonoM\BetterNavigator.SHOW_TEMPLATE_LABEL 'Show template' %>
                </a>
                <a href="{$AbsoluteLink}?execmetric=1"
                   title="<%t JonoM\BetterNavigator.EXEC_METRIC_TITLE 'Display the execution time and peak memory usage for the request' %>"
                >
                    <span class="bn-icon-info"></span>
                    <%t JonoM\BetterNavigator.SHOW_METRICS_LABEL 'Show metrics' %>
                </a>
                <a href="{$AbsoluteLink}?debug=1"
                   title="<%t JonoM\BetterNavigator.DEBUG_PAGE_TITLE 'Show a collection of debugging information about the director / controller operation' %>"
                >
                    <span class="bn-icon-info"></span>
                    <%t JonoM\BetterNavigator.DEBUG_PAGE_LABEL 'Debug page' %>
                </a>
                <a href="{$AbsoluteLink}?debug_request=1"
                   title="<%t JonoM\BetterNavigator.DEBUG_REQUEST_TITLE 'Show all steps of the request from initial HTTPRequest to Controller to Template Rendering' %>"
                >
                    <span class="bn-icon-info"></span>
                    <%t JonoM\BetterNavigator.DEBUG_REQUEST_LABEL 'Debug request' %>
                </a>
                <a href="{$AbsoluteLink}?debugfailover=1"
                   title="<%t JonoM\BetterNavigator.DEBUG_FAILOVER_TITLE 'Shows failover methods from classes extended' %>"
                >
                    <span class="bn-icon-info"></span>
                    <%t JonoM\BetterNavigator.DEBUG_FAILOVER_LABEL 'Debug failover' %>
                </a>
                <a href="{$AbsoluteLink}?showqueries=1"
                   title="<%t JonoM\BetterNavigator.SHOW_QUERIES_TITLE 'List all SQL queries executed' %>"
                >
                    <span class="bn-icon-info"></span>
                    <%t JonoM\BetterNavigator.SHOW_QUERIES_LABEL 'Show queries' %>
                </a>
                <a href="{$AbsoluteLink}?previewwrite=1"
                   title="<%t JonoM\BetterNavigator.PREVIEW_WRITE_TITLE "List all insert / update SQL queries, and don't execute them. Useful for previewing writes to the database" %>"
                >
                    <span class="bn-icon-info"></span>
                    <%t JonoM\BetterNavigator.PREVIEW_WRITE_LABEL 'Preview write' %>
                </a>

            </div>

            <% include BetterNavigator\BetterNavigatorExtraDebugging %>

        <% end_if %>

    </div>

</div>

<script type="application/javascript" src="$ScriptUrl"></script>
<link rel="stylesheet" href="$CssUrl" />
