<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class BlogSettings extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('blog-settings', function(Blueprint $table){
            $table->id();
            $table->string('blog_name');
            $table->string('welcome_text');
            $table->text('blog_logo');
            $table->text('cover_image');
            $table->timestamps();
        });

        DB::table('blog-settigns')->create([
            'blog_name' => 'The TechBlog',
            'welcome_text' => 'Welcome to The TechBlog',
            'blog_logo' => 'logo.png',
            'cover_image' => 'cover.png',
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
