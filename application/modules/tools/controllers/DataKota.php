<?php

/**
 * Created by PhpStorm.
 * User: aziz
 * Date: 5/6/2019
 * Time: 8:39 PM
 */
class DataKota extends MX_Controller
{

    public function __construct()
    {
        parent::__construct();
        // if (!isset($this->session->login['id'])) {
        //     gotoLogin();
        // }
    }

    public function insert(){


        // Database connection


        $file = '/path/to/your/csvfile.csv'; // Specify the path to your CSV file
        if (($handle = fopen($file, 'r')) !== FALSE) {
            // Skip the header row
            fgetcsv($handle);

            // Prepare SQL insert statement
            $stmt = $conn->prepare("INSERT INTO postal_codes (postal_id, subdis_id, dis_id, city_id, prov_id, postal_code, subdis_name, dis_name, city_name, prov_name) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

            // Loop through each row in the CSV
            while (($data = fgetcsv($handle, 1000, ',')) !== FALSE) {
                $stmt->bind_param('iiiiiissss',
                    $data[0],  // postal_id
                    $data[1],  // subdis_id
                    $data[2],  // dis_id
                    $data[3],  // city_id
                    $data[4],  // prov_id
                    $data[5],  // postal_code
                    $data[6],  // subdis_name
                    $data[7],  // dis_name
                    $data[8],  // city_name
                    $data[9]   // prov_name
                );

                // Execute the insert
                $stmt->execute();
            }

            // Close the statement and file
            $stmt->close();
            fclose($handle);

            echo "CSV data imported successfully.";
        }
        else {
            echo "Error opening the file.";
        }

        // Close the database connection
        $conn->close();


    }

    public function import_csv() {
        $this->load->model('Mdls/MdlPostalCodes');
        // Path file CSV
        $file_path = './uploads/data_kota.csv'; // Pastikan file ada di folder uploads

        if (($handle = fopen($file_path, 'r')) !== FALSE) {
            $data = [];
            $header = fgetcsv($handle); // Skip header row
            $counter = 0;
            while (($row = fgetcsv($handle, 1000, ',')) !== FALSE) {
                $counter++;
                $data[] = [
                    'postal_id'   => $row[0],
                    'subdis_id'   => $row[1],
                    'dis_id'      => $row[2],
                    'city_id'     => $row[3],
                    'prov_id'     => $row[4],
                    'postal_code' => $row[5],
                    'subdis_name' => $row[6],
                    'dis_name'    => $row[7],
                    'city_name'   => $row[8],
                    'prov_name'   => $row[9],
                ];
            }
            fclose($handle);

            // Masukkan data ke database
            if (!empty($data)) {
                $this->db->trans_start();
                $this->MdlPostalCodes->insert_batch($data);
                showLast_query("biru");
                $this->db->trans_complete();
                echo "Data berhasil diimpor. $counter row";
            } else {
                echo "Tidak ada data yang diimpor.";
            }
        } else {
            echo "Gagal membuka file.";
        }
    }



}