{!! $PHP_OPENING_TAG !!}
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class {{ $migration->name() }} extends Migration
{
/**
* Run the migrations.
*
* @return void
*/
public function up()
{
@if($migration->tableIsPersisted())
    Schema::table('{{ $migration->table() }}', function (Blueprint $table){
@else
    Schema::create('{{ $migration->table() }}', function (Blueprint $table){
@endif
@foreach($migration->actions() as $action)
    @foreach($action->migrationUp() as $line)
        {!! $line !!}
    @endforeach
@endforeach
});
}

/**
* Reverse the migrations.
*
* @return void
*/
public function down()
{
@if($migration->tableIsPersisted())
    Schema::table('{{ $migration->table() }}', function (Blueprint $table) {
    @foreach($migration->actions() as $action)
        @foreach($action->migrationDown() as $line)
            {!! $line !!}
        @endforeach
    @endforeach
    });
@else
    Schema::drop('{{ $migration->table() }}');
@endif
}
}
