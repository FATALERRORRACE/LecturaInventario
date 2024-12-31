<ul>
    @foreach ($dataTree as $keyB => $branch)
        <li id="node_{{ $keyB }}">{{ $branch['Clasificacion'] }}
        </li>
    @endforeach
</ul>