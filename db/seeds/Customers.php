<?php


use Phinx\Seed\AbstractSeed;

class Customers extends AbstractSeed
{
    /**
     * Run Method.
     *
     * Write your database seeder using this method.
     *
     * More information on writing seeders is available here:
     * http://docs.phinx.org/en/latest/seeding.html
     */
    public function run()
    {
        $data = [
            [
                'name'    => 'Franta',
                'date_of_birth' => '1969-06-03',
                'wallet'=>500,
            ],
            [
                'name'    => 'Tomáš',
                'date_of_birth' => '2010-11-20',
                'wallet'=>200,
            ],
            [
                'name'    => 'Michal',
                'date_of_birth' => '2000-07-13',
                'wallet'=>50,
            ],
        ];

        $customers = $this->table('customer');
        $customers->insert($data)->save();    
    }
}
