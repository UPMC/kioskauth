<?php

# This file rewrites some php_printer functions in order to run
# on Linux systems. It uses php_ps extension instead.

function printer_open()
{
  return true;
}

function printer_set_option()
{

}

function printer_create_font()
{

}

function printer_start_doc()
{

}

function printer_start_page()
{

}

function printer_draw_bmp()
{

}

function printer_select_font()
{

}

function printer_draw_text()
{

}

function printer_delete_font()
{

}

function printer_close()
{

}
