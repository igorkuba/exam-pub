<?php


use Phinx\Seed\AbstractSeed;

class Products extends AbstractSeed
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
                'name'    => 'Pivo 10 - 0,3',
                'price' => '20',
                'alcohol'=>1,
            ],
            [
                'name'    => 'Pivo 10 - 0,5',
                'price' => '30',
                'alcohol'=>1,
            ],
            [
                'name'    => 'Pivo 10 - 1l',
                'price' => '50',
                'alcohol'=>1,
            ],
            [
                'name'    => 'Limo 0,3',
                'price' => '20',
                'alcohol'=>0,
            ],
            [
                'name'    => 'Limo 0,4',
                'price' => '25',
                'alcohol'=>0,
            ],
            [
                'name'    => 'Limo 0,5',
                'price' => '30',
                'alcohol'=>0,
            ],
            [
                'name'    => 'Víno 2dcl',
                'price' => '55',
                'alcohol'=>1,
            ],
        ];

        $products = $this->table('product');
        $products->insert($data)->save();
    }
}
