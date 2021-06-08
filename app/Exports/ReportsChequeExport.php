<?php

namespace App\Exports;

use App\Page;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use App\Receipt;

class ReportsChequeExport implements FromCollection,WithHeadings {

  public function headings(): array {
    return [
       "Recibo","Factura","Método de pago","Banco","Cheque no" ,"Cantidad","Fecha de deposito"
    ];
  }

  /**
  * @return \Illuminate\Support\Collection
  */
  public function collection() {

     return collect(Receipt::getData());
     // return Page::getUsers(); // Use this if you return data from Model without using toArray().
  }
}