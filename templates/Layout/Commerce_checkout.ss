<div class="commerce-content-container typography">
    <h1>$Title</h1>

    <% if $Login %>
        <div class="units-row">
            <div class="unit-33">
                $LoginForm
            </div>
            <div class="unit-33">
                $Content
            </div>
        </div>
    <% else %>
        $Form
    <% end_if %>
</div>