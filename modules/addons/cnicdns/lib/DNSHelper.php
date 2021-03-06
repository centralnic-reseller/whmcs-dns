<?php

namespace CNIC\WHMCS\DNS;

use Illuminate\Database\Capsule\Manager as DB;
use Illuminate\Database\Schema\Blueprint;

class DNSHelper
{
    /**
     * Create DB schema on module activation
     */
    public static function createSchema(): void
    {
        DB::schema()->create('mod_cnicdns_templates', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 32);
            $table->boolean('default');
            $table->text('zone');
            // @phpstan-ignore-next-line
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            // @phpstan-ignore-next-line
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
        });

        DB::schema()->create('mod_cnicdns_products', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('template_id')->index();
            $table->integer('product_id')->unique();
            // @phpstan-ignore-next-line
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            // @phpstan-ignore-next-line
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
        });
    }

    /**
     * Update DB schema on module update
     * @param string $version
     */
    public static function updateSchema(string $version): void
    {
    }

    /**
     * Drop DB schema on module deactivation
     */
    public static function dropSchema(): void
    {
        DB::schema()->dropIfExists('mod_cnicdns_products');
        DB::schema()->dropIfExists('mod_cnicdns_templates');
    }

    /**
     * @return \Illuminate\Support\Collection<mixed>
     */
    public static function getConfig(): \Illuminate\Support\Collection
    {
        return DB::table('tbladdonmodules AS m')
            ->where('m.module', '=', 'cnicdns')
            ->pluck('m.value', 'm.setting');
    }

    /**
     * @param int $domainId
     * @return string
     */
    public static function getDomainRegistrar(int $domainId): string
    {
        return DB::table('tbldomains')
            ->where('id', '=', $domainId)
            ->value('registrar');
    }

    /**
     * @return bool
     */
    public static function compatibleRegistrarActive(): bool
    {
        return DB::table('tblregistrars')
            ->whereIn('registrar', ['ispapi', 'keysystems'])
            ->exists();
    }

    /**
     * Get the base64 encoded CentralNic logo
     * @return string
     */
    public static function getLogo(): string
    {
        $data = file_get_contents(__DIR__ . '/../logo.png');
        return $data ? 'data:image/png;base64,' . base64_encode($data) : '';
    }
}
