@extends('layouts.app')

@section('content')
    <div class="pageHolder">
        <div class="pagePanelHolder">
            <div class="pagePanelHeading">
                <div class="captionHolder">
                    <div class="icoHolder">
                        <i class="material-icons">credit_card</i>
                    </div>
                    <div class="caption">Transactions</div>
                </div>
            </div>
            <div class="pagePanelBody">
                <div class="tableHolder">
                    @if(count($logs)>0)
                        <table class="table">
                            <thead class="text-warning">
                            <tr>
                                <th>signature</th>
                                <th>transaction</th>
                                <th>data</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach( $logs as $log)
                                <tr>
                                    <td>{{ $log['signature'] }}</td>
                                    <td style="font-size: 0.7em">
                                        <a target="_blank" href="https://rinkeby.etherscan.io/tx/{{ $log['logInfo']['transactionHash'] ?? '' }}">{{ $log['logInfo']['transactionHash'] ?? '' }}</a>

                                    </td>
                                    <td style="font-size: 0.7em">{{ json_encode($log) }}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection