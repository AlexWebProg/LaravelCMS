@extends('admin.subscribes.edit.layout')

@section('subscribers_form_section')
    <div class="row">
        <div class="col-12">
            <div class="timeline">
                @foreach ($subscribe->history as $history)
                    <div>
                        <i class="fa {{ $history->history_icon }}" aria-hidden="false"></i>
                        <div class="timeline-item">
                            <span class="time"><i class="fa fa-clock"></i> {{ $history->date }}</span>
                            <h3 class="timeline-header">
                                <a data-toggle="collapse" href="#collapse{{ $history->id }}" aria-expanded="false" aria-controls="collapse{{ $history->id }}">
                                    <b>{{ $history->history_header }}</b>
                                </a>
                            </h3>

                            <div class="timeline-body pl-4 collapse" id="collapse{{ $history->id }}">
                                @foreach ($history->info as $history_info_block1_name => $history_info_block1_content)
                                    @if (is_object($history_info_block1_content))
                                        <p><b>{{ $history_info_block1_name }}</b></p>
                                        <div class="ml-4 pb-3">
                                            @foreach ($history_info_block1_content as $history_info_block2_name => $history_info_block2_content)
                                                <p>{{ $history_info_block2_name }}: <b>{{ $history_info_block2_content }}</b></p>
                                            @endforeach
                                        </div>
                                    @else
                                        <p class="pb-3">{{ $history_info_block1_name }}: <b>{{ $history_info_block1_content }}</b></p>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endforeach
                <div>
                    <i class="fa fa-clock-o bg-gray" aria-hidden="false"></i>
                </div>
            </div>
        </div>
    </div>
@endsection
