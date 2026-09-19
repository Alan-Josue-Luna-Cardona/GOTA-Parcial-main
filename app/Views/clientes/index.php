<?php
/** @var array $clientes */
/** @var bool $mostrarInactivos */
?>
<?= $this->extend('layouts/main') ?>

<?= $this->section('title') ?>Clientes<?= $this->endSection() ?>

<?= $this->section('styles') ?>
<style>
    .clientes-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 12px;
        margin-bottom: 20px;
    }

    .clientes-header h2 {
        margin: 0;
        color: #1a1a2e;
        font-weight: 800;
    }

    .table-clientes th {
        color: #6c757d;
        font-size: 0.85rem;
        text-transform: uppercase;
        letter-spacing: 0.03em;
    }

    .badge-inactivo {
        background-color: #e9ecef;
        color: #6c757d;
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container-fluid py-4">

    <div class="clientes-header">
        <h2>Clientes</h2>
        <a href="/clientes/nuevo" class="btn btn-primary">
            <i class="fa-solid fa-plus me-1"></i> Nuevo cliente
        </a>
    </div>

    <?php if (session()->getFlashdata('mensaje')): ?>
        <div class="alert alert-success" role="alert"><?= esc(session()->getFlashdata('mensaje')) ?></div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger" role="alert"><?= esc(session()->getFlashdata('error')) ?></div>
    <?php endif; ?>

    <div class="mb-3">
        <?php if ($mostrarInactivos): ?>
            <a href="/clientes" class="btn btn-sm btn-outline-secondary">Ver solo activos</a>
        <?php else: ?>
            <a href="/clientes?mostrar=inactivos" class="btn btn-sm btn-outline-secondary">Ver todos (incluye inactivos)</a>
        <?php endif; ?>
    </div>

    <div class="table-responsive">
        <div class="card shadow-sm">
            <div class="card-body p-0">
            <table class="table table-hover table-clientes mb-0">
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Teléfono</th>
                        <th>Dirección</th>
                        <th>Email</th>
                        <th>Registro</th>
                        <th>Estado</th>
                        <th class="text-end">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($clientes)): ?>
                        <tr>
                            <td colspan="7" class="text-center text-muted py-4">No hay clientes para mostrar.</td>
                        </tr>
                    <?php endif; ?>

                    <?php foreach ($clientes as $cliente): ?>
                        <tr>
                            <td><?= esc($cliente['nombre']) ?></td>
                            <td><?= esc($cliente['telefono'] ?? '-') ?></td>
                            <td><?= esc($cliente['direccion']) ?></td>
                            <td><?= esc($cliente['email'] ?? '-') ?></td>
                            <td><?= esc($cliente['fecha_registro']) ?></td>
                            <td>
                                <?php if ($cliente['activo']): ?>
                                    <span class="badge bg-success">Activo</span>
                                <?php else: ?>
                                    <span class="badge badge-inactivo">Inactivo</span>
                                <?php endif; ?>
                            </td>
                            <td class="text-end">
                                <a href="/clientes/editar/<?= $cliente['id'] ?>" class="btn btn-sm btn-outline-primary">
                                    Editar
                                </a>
                                <?php if ($cliente['activo']): ?>
                                    <form action="/clientes/desactivar/<?= $cliente['id'] ?>" method="post" class="d-inline"
                                          onsubmit="return confirm('¿Desactivar este cliente?');">
                                        <?= csrf_field() ?>
                                        <button type="submit" class="btn btn-sm btn-outline-danger">Desactivar</button>
                                    </form>
                                <?php else: ?>
                                    <form action="/clientes/activar/<?= $cliente['id'] ?>" method="post" class="d-inline">
                                        <?= csrf_field() ?>
                                        <button type="submit" class="btn btn-sm btn-outline-success">Activar</button>
                                    </form>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            </div>
        </div>
    </div>

</div>
<?= $this->endSection() ?>