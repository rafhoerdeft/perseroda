<?php

namespace App\Http\Controllers\Transaksi\Out;

use App\Http\Controllers\UserBaseController;
use App\Models\Nota as ModelsNota;
use App\Models\Rekanan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
// use Yajra\DataTables\DataTables;

class Nota extends UserBaseController
{
    protected $is_role;

    public function __construct()
    {
        parent::__construct();

        $this->middleware(function ($request, $next) {
            $this->is_role = false;
            $role = ['bendahara'];
            if (in_array(Auth::user()->role->nama_role, $role)) {
                $this->is_role = true;
            }
            return $next($request);
        });
    }

    public function index(Request $request)
    {
        $breadcrumb = ['Nota Pembelian'];
        $form_title = 'Input Nota';

        $status_bayar = [
            'Belum Bayar',
            'Lunas'
        ];

        $status_bayar_select = $request->status_bayar;
        $jenis_bayar_select = $request->jenis_bayar;

        $is_role = $this->is_role;

        $main_route = 'transaksi.out.nota.';

        $list_data = ModelsNota::with('rekanan')->latest()->get();

        $data = compact(
            'breadcrumb',
            'form_title',
            'is_role',
            'status_bayar',
            'status_bayar_select',
            'jenis_bayar_select',
            'main_route',
            'list_data',
        );

        return view('pages/transaksi/out/nota/list', $data);
    }

    public function getRekanan(Request $request)
    {
        try {
            $limit = $request->limit ?? 10;
            $rekanan = Rekanan::where('nama', 'LIKE', '%' . $request->search . '%')
                ->orWhere('alamat', 'LIKE', '%' . $request->search . '%');

            $count = $rekanan->count();
            $result = $rekanan->offset((($request->page - 1) * $limit))->limit($limit)->get();

            if (!$result) {
                throw new \Exception('Gagal mengambil data rekanan.');
            }

            $res = [
                'response' => true,
                'count' => $count,
                'result' => $result
            ];
        } catch (\Exception $e) {
            $res = ['response' => false, 'result' => $e->getMessage()];
        }

        return json_encode($res);
    }

    public function add()
    {
        $year = selected_year;
        $main_route = 'transaksi.out.nota.';

        $rekanan = Rekanan::get();

        $breadcrumb = ['transaksi/out/nota' => 'Nota Pembelian', 'Form Input']; //url => title
        $form_title = 'Input Nota';
        return view('pages/transaksi/out/nota/form', compact(
            'breadcrumb',
            'form_title',
            'rekanan',
            'year',
            'main_route'
        ));
    }

    public function edit($id = null)
    {
        $year = selected_year;
        $nota = ModelsNota::find(decode($id));
        $rekanan = Rekanan::get();

        $no_nota = $nota->no_nota;

        $main_route = 'transaksi.out.nota.';

        $breadcrumb = ['transaksi/out/nota' => 'Nota Pembelian', 'Form Input']; //url => title
        $form_title = 'Edit Nota - ' . $no_nota;
        return view('pages/transaksi/out/nota/form', compact(
            'breadcrumb',
            'form_title',
            'nota',
            'year',
            'rekanan',
            'main_route'
        ));
    }

    public function save(Request $request)
    {
        $request->validate([
            'tgl_nota'  => 'required|date_format:d/m/Y',
            'no_nota'  => 'string|nullable',
            'rekanan'  => 'required',
            'jenis_bayar'  => 'required|in:tunai,bank',
            'status_bayar'  => 'required|numeric|between:0,1',
            'harga_total'  => 'required|regex:/^[0-9\.,]+$/|not_in:0',
            'ket_nota'  => 'string|nullable',
        ]);

        DB::beginTransaction();
        try {
            $data_nota = [
                'user_id'   => Auth::user()->id,
                'tgl_nota' => re_date_format($request->tgl_nota),
                'no_nota' => $request->no_nota ?? '-',
                'rekanan_id' => decode($request->rekanan),
                'status_bayar' => $request->status_bayar,
                'jenis_bayar' => $request->jenis_bayar,
                'harga_total' => rm_nominal($request->harga_total),
                'ket_nota' => $request->ket_nota,
            ];

            if ($request->nota_id) {
                $nota_id = decode($request->nota_id);
            } else {
                $nota_id = null;
            }

            if ($nota_id == null) {
                $nota = ModelsNota::create($data_nota);
                $nota_id = $nota->id;
            } else {
                $nota = ModelsNota::find($nota_id)->update($data_nota);
            }

            if (!$nota) {
                throw new \Exception("Gagal simpan data nota");
            }

            DB::commit();
            alert_success('Data nota berhasil disimpan.');
        } catch (\Exception $e) {
            DB::rollBack();
            alert_failed('Data nota gagal disimpan.' . $e->getMessage());
        }

        if ($request->nota_id) {
            return redirect('transaksi/out/nota');
        } else {
            return redirect('transaksi/out/nota/add');
        }
    }

    public function delete(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), ['id' => 'required']);

            if ($validator->fails()) {
                throw new \Exception($validator->errors());
            }

            $id = $request->id;
            $deleted = ModelsNota::find(decode($id))->delete();
            if (!$deleted) {
                throw new \Exception('Gagal hapus data!');
            }

            $res = ['success' => true];
        } catch (\Exception $e) {
            $res = ['success' => false, 'alert' => $e->getMessage()];
        }

        return json_encode($res);
    }

    public function changeStatusBayar(Request $request)
    {
        DB::beginTransaction();
        try {
            $data_nota = [
                'status_bayar' => 1,
            ];

            $nota_id = decode($request->id);

            $nota = ModelsNota::find($nota_id)->update($data_nota);

            if (!$nota) {
                throw new \Exception("Gagal ubah status bayar");
            }

            DB::commit();
            $res = ['response' => true, 'text' => 'Berhasil ubah status bayar'];
        } catch (\Exception $e) {
            DB::rollBack();
            $res = ['response' => false, 'text' => $e->getMessage()];
        }

        return json_encode($res);
    }

    public function changeJenisBayar(Request $request)
    {
        DB::beginTransaction();
        try {
            $data_nota = [
                'jenis_bayar' => $request->jenis,
            ];

            $nota_id = decode($request->id);

            $nota = ModelsNota::find($nota_id)->update($data_nota);

            if (!$nota) {
                throw new \Exception("Gagal ubah jenis bayar");
            }

            DB::commit();
            $res = ['response' => true, 'text' => 'Berhasil ubah jenis bayar'];
        } catch (\Exception $e) {
            DB::rollBack();
            $res = ['response' => false, 'text' => $e->getMessage()];
        }

        return json_encode($res);
    }
}
