<div class="pagination">
    <table class="w-100">
        <tbody>
            <tr>
                <td class="text">
                    {{ $model->appends(prepareInputRequestArray())->links() }}
                    <span>Showing {{$model->toArray()['from']}} to {{$model->toArray()['to']}} of {{ $model->total() }} record(s)&nbsp;&nbsp;&nbsp;&nbsp;</span>
                    <span style="float:right">Page Count : &nbsp;
                        @foreach (getPerPageOptions() as $item)
                            <a href="{{ $model->appends(prepareInputRequestArray(['per_page'=>$item]))->url(1) }}" class="{{ $model->perPage() == $item ? 'active' : '' }}">{{ $item }}</a>&nbsp;
                        @endforeach
                    </span>
                </td>
            </tr>
        </tbody>
    </table>
</div>