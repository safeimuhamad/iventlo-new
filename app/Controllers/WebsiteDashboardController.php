<?php


class WebsiteDashboardController extends Controller
{
    public function index()
    {
        requirePermission('website_dashboard.view');

        $model = new WebsiteDashboard();

        $this->view('website/dashboard/index', [
            'title' => 'Website Dashboard',
            'activeSliders' => $model->countActiveSliders(),
            'todayInquiries' => $model->countTodayInquiries(),
            'monthInquiries' => $model->countThisMonthInquiries(),
            'totalInquiries' => $model->countTotalInquiries(),
            'latestInquiries' => $model->latestInquiries(5)
        ]);
    }
}