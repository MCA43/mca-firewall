<?php

namespace Mca\Firewall\Http\Controllers\Web;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\View\View;
use Mca\Firewall\Http\Requests\StoreRuleRequest;
use Mca\Firewall\Http\Requests\UpdateRuleRequest;
use Mca\Firewall\Models\FirewallRule;
use Mca\Firewall\Services\FirewallService;
use Mca\Firewall\Support\McaFirewallView;

class RuleController extends Controller
{
    public function __construct(private readonly FirewallService $firewall) {}

    public function index(Request $request): View
    {
        $type = (string) $request->query('type', 'all');
        $q = trim((string) $request->query('q', ''));

        $query = FirewallRule::query()->latest('id');

        if (in_array($type, [FirewallRule::TYPE_WHITELIST, FirewallRule::TYPE_BLACKLIST], true)) {
            $query->ofType($type);
        }

        if ($q !== '') {
            $query->where(function ($builder) use ($q): void {
                $builder->where('ip', 'like', '%'.$q.'%')
                    ->orWhere('label', 'like', '%'.$q.'%')
                    ->orWhere('reason', 'like', '%'.$q.'%');
            });
        }

        return McaFirewallView::render('rules.index', [
            'rules' => $query->paginate(20)->withQueryString(),
            'filterType' => $type,
            'search' => $q,
            'counts' => [
                'all' => FirewallRule::query()->count(),
                'whitelist' => FirewallRule::query()->ofType(FirewallRule::TYPE_WHITELIST)->count(),
                'blacklist' => FirewallRule::query()->ofType(FirewallRule::TYPE_BLACKLIST)->count(),
                'trash' => FirewallRule::onlyTrashed()->count(),
            ],
        ]);
    }

    public function create(): View
    {
        return McaFirewallView::render('rules.create', [
            'rule' => new FirewallRule([
                'type' => FirewallRule::TYPE_BLACKLIST,
                'is_active' => true,
            ]),
        ]);
    }

    public function store(StoreRuleRequest $request): RedirectResponse
    {
        $this->firewall->create($request->validated(), $request->user()?->getAuthIdentifier());

        return redirect()
            ->route(config('firewall.routes.web.name_prefix', 'mca.firewall.').'index')
            ->with('mca_fw_status', mca_fw('flash.created'));
    }

    public function edit(int $rule): View
    {
        $model = FirewallRule::query()->findOrFail($rule);

        return McaFirewallView::render('rules.edit', [
            'rule' => $model,
        ]);
    }

    public function update(UpdateRuleRequest $request, int $rule): RedirectResponse
    {
        $model = FirewallRule::query()->findOrFail($rule);
        $this->firewall->update($model, $request->validated());

        return redirect()
            ->route(config('firewall.routes.web.name_prefix', 'mca.firewall.').'index')
            ->with('mca_fw_status', mca_fw('flash.updated'));
    }

    public function destroy(int $rule): RedirectResponse
    {
        $model = FirewallRule::query()->findOrFail($rule);
        $this->firewall->delete($model);

        return redirect()
            ->route(config('firewall.routes.web.name_prefix', 'mca.firewall.').'index')
            ->with('mca_fw_status', mca_fw('flash.trashed'));
    }

    public function toggle(int $rule): RedirectResponse
    {
        $model = FirewallRule::query()->findOrFail($rule);
        $this->firewall->toggle($model);

        return back()->with('mca_fw_status', mca_fw('flash.toggled'));
    }

    public function trash(): View
    {
        return McaFirewallView::render('rules.trash', [
            'rules' => FirewallRule::onlyTrashed()->latest('deleted_at')->paginate(20),
            'counts' => [
                'all' => FirewallRule::query()->count(),
                'trash' => FirewallRule::onlyTrashed()->count(),
            ],
        ]);
    }

    public function restore(int $rule): RedirectResponse
    {
        $model = FirewallRule::onlyTrashed()->findOrFail($rule);
        $this->firewall->restore($model);

        return redirect()
            ->route(config('firewall.routes.web.name_prefix', 'mca.firewall.').'trash')
            ->with('mca_fw_status', mca_fw('flash.restored'));
    }

    public function forceDestroy(int $rule): RedirectResponse
    {
        $model = FirewallRule::onlyTrashed()->findOrFail($rule);
        $this->firewall->forceDelete($model);

        return redirect()
            ->route(config('firewall.routes.web.name_prefix', 'mca.firewall.').'trash')
            ->with('mca_fw_status', mca_fw('flash.force_deleted'));
    }
}
