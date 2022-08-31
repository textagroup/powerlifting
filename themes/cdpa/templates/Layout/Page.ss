<% include SideBar %>
<div class="content-container unit size3of4 lastUnit">
	<article>
		<h1>$Title</h1>
		<div class="content">$Content</div>
        <% if $RecordTables %>
        <div class="table-responsive">
            <table class="table">
                <tr>
                    <th>
                    </th>
                    <% if $IsBenchOnly == 0 %>
                        <th colspan=3>
                            Squat
                        </th>
                    <%end_if %>
                    <th colspan=3>
                        Bench
                    </th>
                    <% if $IsBenchOnly == 0 %>
                        <th colspan=3>
                            Deadlift
                        </th>
                        <th colspan=3>
                            Total
                        </th>
                    <%end_if %>
                </tr>
                <tr>
                    <th>
                        Division
                    </th>
                    <th>
                        Name
                    </th>
                    <th>
                        Meet
                    </th>
                    <th>
                        Weight
                    </th>
                    <% if $IsBenchOnly == 0 %>
                        <th>
                            Name
                        </th>
                        <th>
                            Meet
                        </th>
                        <th>
                            Weight
                        </th>
                        <th>
                            Name
                        </th>
                        <th>
                            Meet
                        </th>
                        <th>
                            Weight
                        </th>
                        <th>
                            Name
                        </th>
                        <th>
                            Meet
                        </th>
                        <th>
                            Weight
                        </th>
                    <% end_if %>
                </tr>

                <% loop $RecordTables %>
                    <tr>
                        <td>$LifterWeightClass</td>
                        <% if $Top.IsBenchOnly == 0 %>
                            <td>$SquatRecordHolder</td>
                            <td>$SquatRecordMeet</td>
                            <td>$SquatRecordWeight</td>
                        <% end_if %>
                        <td>$BenchRecordHolder</td>
                        <td>$BenchRecordMeet</td>
                        <td>$BenchRecordWeight</td>
                        <% if $Top.IsBenchOnly == 0 %>
                            <td>$DeadliftRecordHolder</td>
                            <td>$DeadliftRecordMeet</td>
                            <td>$DeadliftRecordWeight</td>
                            <td>$TotalRecordHolder</td>
                            <td>$TotalRecordMeet</td>
                           <td>$TotalRecordWeight</td>
                        <% end_if %>
                    </tr>
                <% end_loop %>
            </table>
        </div>
        <% end_if %>
	</article>
		$Form
		$CommentsForm
</div>