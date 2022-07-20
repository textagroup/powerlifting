<% include SideBar %>
<div class="content-container unit size3of4 lastUnit">
	<article>
		<h1>$Title</h1>
		<div class="content">$Content</div>
        <table>
            <tr>
                <th>
                </th>
                <th colspan=3>
                    Squat
                </th>
                <th colspan=3>
                    Bench
                </th>
                <th colspan=3>
                    Deadlift
                </th>
                <th colspan=3>
                    Total
                </th>
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
            </tr>

            <% loop $RecordTables %>
                <tr>
                    <td>$MaxWeight</td>
                    <td>$SquatRecordHolder</td>
                    <td>$SquatRecordMeet</td>
                    <td>$SquatRecordWeight</td>
                    <td>$BenchRecordHolder</td>
                    <td>$BenchRecordMeet</td>
                    <td>$BenchRecordWeight</td>
                    <td>$DeadliftRecordHolder</td>
                    <td>$DeadliftRecordMeet</td>
                    <td>$DeadliftRecordWeight</td>
                    <td>$TotalRecordHolder</td>
                    <td>$TotalRecordMeet</td>
                    <td>$TotalRecordWeight</td>
                </tr>
            <% end_loop %>
        </table>
	</article>
		$Form
		$CommentsForm
</div>