<div id="rack" class="flex-container">
    <!--  check if it is set -->
    @if(isset($valeur))
        @foreach($valeur as $i)

            @if($i && $i!= null && $i!='')
                <div class="flex-item selected">
                    <div class="top-left">{{$i->lettre}}<sub class="number">{{$i->valeur}}</sub></div>
                </div>
            @else
                <div class="flex-item selected">
                    <div class="top-left"></div>
                </div>
            @endif
        @endforeach
    @endif
</div>