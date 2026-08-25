import React, { useEffect, useState } from "react";
import { useNavigate } from "react-router-dom";
import {
    Add as AddIcon,
    Assignment as AssignmentIcon,
    Cancel as CancelIcon,
    CheckCircle as CheckCircleIcon,
    Edit as EditIcon,
    ExitToApp as LogoutIcon,
    HourglassEmpty as HourglassIcon,
    Notifications as NotificationsIcon,
    Payment as PaymentIcon,
    Settings as SettingsIcon,
    Warning as WarningIcon,
} from "@mui/icons-material";
import {
    Alert,
    AlertTitle,
    Badge,
    Box,
    Button,
    Card,
    CardContent,
    Chip,
    CircularProgress as LoadingSpinner,
    Container,
    Divider,
    Grid,
    IconButton,
    Paper,
    Stack,
    Typography,
} from "@mui/material";
import useAuth from "../hooks/useAuth";
import vendorApi, {
    ActivityItem,
    AssignedStageItem,
    DashboardAlerts,
    DashboardSummary,
    VendorProfile,
} from "../api/vendorApi";
import BottomNav from "../components/BottomNav";

export const Dashboard: React.FC = () => {
    const navigate = useNavigate();
    const { user, logout } = useAuth();

    const [loading, setLoading] = useState(true);
    const [vendorProfile, setVendorProfile] = useState<VendorProfile | null>(
        null,
    );
    const [alertsData, setAlertsData] = useState<DashboardAlerts | null>(null);
    const [summaryData, setSummaryData] = useState<DashboardSummary | null>(
        null,
    );
    const [activityData, setActivityData] = useState<ActivityItem[]>([]);
    const [assignedData, setAssignedData] = useState<AssignedStageItem[]>([]);

    // Bottom Navigation tab state
    const [navTab, setNavTab] = useState(0);

    useEffect(() => {
        const fetchDashboardData = async () => {
            setLoading(true);
            try {
                const profile = await vendorApi.getMe();
                setVendorProfile(profile);

                if (profile.approval_status === "approved") {
                    const [alerts, summary, activity, assigned] =
                        await Promise.all([
                            vendorApi.getAlerts(),
                            vendorApi.getSummary(),
                            vendorApi.getActivity(),
                            vendorApi.getAssigned(),
                        ]);
                    setAlertsData(alerts);
                    setSummaryData(summary);
                    setActivityData(activity);
                    setAssignedData(assigned);
                }
            } catch (err) {
                console.error("Failed to load vendor dashboard data", err);
            } finally {
                setLoading(false);
            }
        };

        fetchDashboardData();
    }, []);

    if (loading) {
        return (
            <Box
                sx={{
                    minHeight: "100vh",
                    display: "flex",
                    alignItems: "center",
                    justifyContent: "center",
                    background: "#0f172a",
                }}
            >
                <LoadingSpinner size={48} color="primary" />
            </Box>
        );
    }

    const isApproved = vendorProfile?.approval_status === "approved";
    const isRejected = vendorProfile?.approval_status === "rejected";

    /* =========================================================================
       SUB-COMPONENTS
       ========================================================================= */

    // Section 2: Action Needed Banner
    const renderActionNeeded = () =>
        alertsData?.new_assignments ||
        alertsData?.awaiting_my_assignment ||
        alertsData?.overdue_stages ? (
            <Paper
                elevation={6}
                sx={{
                    padding: 3,
                    borderRadius: 3,
                    background: "rgba(239, 68, 68, 0.1)",
                    border: "1px solid rgba(239, 68, 68, 0.3)",
                }}
            >
                <Box
                    sx={{
                        display: "flex",
                        alignItems: "center",
                        gap: 1,
                        mb: 2,
                    }}
                >
                    <WarningIcon sx={{ color: "#ef4444" }} />
                    <Typography
                        variant="subtitle1"
                        sx={{
                            fontWeight: 800,
                            color: "#ef4444",
                            letterSpacing: "0.5px",
                        }}
                    >
                        🔔 ACTION NEEDED
                    </Typography>
                </Box>

                <Stack spacing={1} sx={{ pl: 1 }}>
                    {alertsData.new_assignments > 0 && (
                        <Box
                            onClick={() => navigate("/assigned")}
                            sx={{ cursor: "pointer" }}
                        >
                            <Typography
                                variant="body2"
                                sx={{
                                    display: "flex",
                                    alignItems: "center",
                                    gap: 1,
                                }}
                            >
                                📥{" "}
                                <strong>
                                    {alertsData.new_assignments} new order
                                </strong>{" "}
                                assigned to you
                            </Typography>
                        </Box>
                    )}
                    {alertsData.awaiting_my_assignment > 0 && (
                        <Box
                            onClick={() => navigate("/assigned")}
                            sx={{ cursor: "pointer" }}
                        >
                            <Typography
                                variant="body2"
                                sx={{
                                    display: "flex",
                                    alignItems: "center",
                                    gap: 1,
                                }}
                            >
                                ➡️{" "}
                                <strong>
                                    {alertsData.awaiting_my_assignment} stages
                                    completed
                                </strong>{" "}
                                – assign next vendor
                            </Typography>
                        </Box>
                    )}
                    {alertsData.overdue_stages > 0 && (
                        <Box
                            onClick={() => navigate("/assigned")}
                            sx={{ cursor: "pointer" }}
                        >
                            <Typography
                                variant="body2"
                                sx={{
                                    display: "flex",
                                    alignItems: "center",
                                    gap: 1,
                                    color: "#fca5a5",
                                }}
                            >
                                ⏰{" "}
                                <strong>
                                    {alertsData.overdue_stages} stage overdue
                                </strong>
                                {alertsData.overdue_details
                                    ? ` (${alertsData.overdue_details})`
                                    : ""}
                            </Typography>
                        </Box>
                    )}
                </Stack>
            </Paper>
        ) : null;

    // Section 3: Summary Cards (4-Grid)
    const renderSummaryCards = () => (
        <Grid container spacing={2}>
            <Grid size={{ xs: 6, sm: 3 }}>
                <Card
                    onClick={() => navigate("/orders")}
                    sx={{
                        bgcolor: "rgba(30, 41, 59, 0.8)",
                        border: "1px solid rgba(255, 255, 255, 0.1)",
                        borderRadius: 3,
                        cursor: "pointer",
                    }}
                >
                    <CardContent sx={{ textAlign: "center", py: 3 }}>
                        <Typography
                            variant="caption"
                            color="text.secondary"
                            sx={{ fontWeight: 700, textTransform: "uppercase" }}
                        >
                            Active Orders
                        </Typography>
                        <Typography
                            variant="h4"
                            sx={{ fontWeight: 800, mt: 1, color: "#6366f1" }}
                        >
                            {summaryData?.active_orders ?? 0}
                        </Typography>
                    </CardContent>
                </Card>
            </Grid>

            <Grid size={{ xs: 6, sm: 3 }}>
                <Card
                    onClick={() => navigate("/assigned")}
                    sx={{
                        bgcolor: "rgba(30, 41, 59, 0.8)",
                        border: "1px solid rgba(255, 255, 255, 0.1)",
                        borderRadius: 3,
                        cursor: "pointer",
                    }}
                >
                    <CardContent sx={{ textAlign: "center", py: 3 }}>
                        <Typography
                            variant="caption"
                            color="text.secondary"
                            sx={{ fontWeight: 700, textTransform: "uppercase" }}
                        >
                            Assigned to Me
                        </Typography>
                        <Typography
                            variant="h4"
                            sx={{ fontWeight: 800, mt: 1, color: "#38bdf8" }}
                        >
                            {summaryData?.assigned_to_me ?? 0}
                        </Typography>
                    </CardContent>
                </Card>
            </Grid>

            <Grid size={{ xs: 6, sm: 3 }}>
                <Card
                    onClick={() => navigate("/assigned")}
                    sx={{
                        bgcolor: "rgba(30, 41, 59, 0.8)",
                        border: "1px solid rgba(255, 255, 255, 0.1)",
                        borderRadius: 3,
                        cursor: "pointer",
                    }}
                >
                    <CardContent sx={{ textAlign: "center", py: 3 }}>
                        <Typography
                            variant="caption"
                            color="text.secondary"
                            sx={{ fontWeight: 700, textTransform: "uppercase" }}
                        >
                            Awaiting Assignment
                        </Typography>
                        <Typography
                            variant="h4"
                            sx={{ fontWeight: 800, mt: 1, color: "#f59e0b" }}
                        >
                            {summaryData?.awaiting_my_assignment ?? 0}
                        </Typography>
                    </CardContent>
                </Card>
            </Grid>

            <Grid size={{ xs: 6, sm: 3 }}>
                <Card
                    onClick={() => navigate("/payments")}
                    sx={{
                        bgcolor: "rgba(30, 41, 59, 0.8)",
                        border: "1px solid rgba(255, 255, 255, 0.1)",
                        borderRadius: 3,
                        cursor: "pointer",
                    }}
                >
                    <CardContent sx={{ textAlign: "center", py: 3 }}>
                        <Typography
                            variant="caption"
                            color="text.secondary"
                            sx={{ fontWeight: 700, textTransform: "uppercase" }}
                        >
                            Monthly Earnings
                        </Typography>
                        <Typography
                            variant="h4"
                            sx={{ fontWeight: 800, mt: 1, color: "#10b981" }}
                        >
                            {summaryData?.monthly_earnings ?? "₹0"}
                        </Typography>
                    </CardContent>
                </Card>
            </Grid>
        </Grid>
    );

    // Section 4: Create Order Button
    const renderCreateOrderButton = () => (
        <Button
            fullWidth
            variant="contained"
            size="large"
            startIcon={<AddIcon />}
            onClick={() => navigate("/orders/create")}
            sx={{
                py: 1.8,
                fontSize: "1.05rem",
                fontWeight: 700,
                borderRadius: 3,
                background: "linear-gradient(135deg, #6366f1 0%, #4f46e5 100%)",
                boxShadow: "0 8px 20px rgba(79, 70, 229, 0.4)",
                "&:hover": {
                    background:
                        "linear-gradient(135deg, #4f46e5 0%, #4338ca 100%)",
                },
            }}
        >
            + Create New Order
        </Button>
    );

    // Assigned to Me — only rendered if there's actual data
    const renderAssignedToList = () => (
        <Paper
            elevation={6}
            sx={{
                padding: 3,
                borderRadius: 3,
                background: "rgba(30, 41, 59, 0.85)",
                border: "1px solid rgba(56, 189, 248, 0.3)",
            }}
        >
            <Box
                sx={{
                    display: "flex",
                    justifyContent: "space-between",
                    alignItems: "center",
                    mb: 1,
                }}
            >
                <Typography
                    variant="h6"
                    sx={{ fontWeight: 700, color: "#38bdf8" }}
                >
                    📋 Assigned to Me
                </Typography>
                <Button
                    size="small"
                    sx={{ textTransform: "none" }}
                    onClick={() => navigate("/assigned")}
                >
                    View All
                </Button>
            </Box>
            <Typography variant="body2" color="text.secondary" sx={{ mb: 2 }}>
                Active stage work assigned to your workshop:
            </Typography>

            <Stack spacing={1.5}>
                {assignedData.slice(0, 5).map((stage) => (
                    <Box
                        key={stage.id}
                        onClick={() => navigate(`/orders/${stage.order_id}`)}
                        sx={{
                            display: "flex",
                            justifyContent: "space-between",
                            bgcolor: "rgba(15, 23, 42, 0.5)",
                            p: 1.5,
                            borderRadius: 2,
                            cursor: "pointer",
                        }}
                    >
                        <Typography variant="body2" sx={{ fontWeight: 600 }}>
                            {stage.order_reference} — {stage.service_name}
                        </Typography>
                        <Chip
                            label={
                                stage.status === "in_progress"
                                    ? "In Progress"
                                    : stage.status === "assigned"
                                      ? "Assigned"
                                      : "Completed"
                            }
                            color={
                                stage.status === "in_progress"
                                    ? "warning"
                                    : stage.status === "assigned"
                                      ? "info"
                                      : "success"
                            }
                            size="small"
                        />
                    </Box>
                ))}
            </Stack>
        </Paper>
    );

    // Section 5: Recent Activity Feed
    const renderActivityFeed = () => (
        <Paper
            elevation={6}
            sx={{
                padding: 3,
                borderRadius: 3,
                background: "rgba(30, 41, 59, 0.8)",
                border: "1px solid rgba(255, 255, 255, 0.1)",
            }}
        >
            <Box
                sx={{
                    display: "flex",
                    justifyContent: "space-between",
                    alignItems: "center",
                    mb: 2,
                }}
            >
                <Typography variant="h6" sx={{ fontWeight: 700 }}>
                    📋 Recent Activity
                </Typography>
                <Button
                    size="small"
                    sx={{ textTransform: "none" }}
                    onClick={() => navigate("/activity")}
                >
                    View All Activity
                </Button>
            </Box>

            <Divider sx={{ mb: 2, borderColor: "rgba(255, 255, 255, 0.1)" }} />

            {activityData.length === 0 ? (
                <Typography variant="body2" color="text.secondary">
                    No recent activity yet.
                </Typography>
            ) : (
                <Stack spacing={2}>
                    {activityData.map((item) => (
                        <Box
                            key={item.id}
                            onClick={() => navigate(`/orders/${item.order_id}`)}
                            sx={{
                                display: "flex",
                                alignItems: "center",
                                justifyContent: "space-between",
                                bgcolor: "rgba(15, 23, 42, 0.5)",
                                p: 2,
                                borderRadius: 2,
                                cursor: "pointer",
                            }}
                        >
                            <Box
                                sx={{
                                    display: "flex",
                                    alignItems: "center",
                                    gap: 1.5,
                                }}
                            >
                                {item.type === "stage_completed" && (
                                    <CheckCircleIcon
                                        sx={{ color: "#10b981" }}
                                    />
                                )}
                                {item.type === "payment_received" && (
                                    <PaymentIcon sx={{ color: "#f59e0b" }} />
                                )}
                                {(item.type === "order_assigned" ||
                                    item.type === "stage_assigned") && (
                                    <AssignmentIcon sx={{ color: "#6366f1" }} />
                                )}

                                <Typography
                                    variant="body2"
                                    sx={{ fontWeight: 500 }}
                                >
                                    {item.title}
                                </Typography>
                            </Box>

                            <Chip
                                label={item.timestamp}
                                size="small"
                                variant="outlined"
                                sx={{
                                    color: "#94a3b8",
                                    borderColor: "rgba(255, 255, 255, 0.2)",
                                }}
                            />
                        </Box>
                    ))}
                </Stack>
            )}
        </Paper>
    );

    const industryNames =
        vendorProfile?.industries.map((i) => i.name).join(", ") || "—";
    const serviceNames =
        vendorProfile?.services.map((s) => s.name).join(", ") || "—";

    return (
        <Box
            sx={{
                minHeight: "100vh",
                background:
                    "linear-gradient(135deg, #0f172a 0%, #1e293b 50%, #0f172a 100%)",
                color: "#ffffff",
                pt: { xs: "96px", sm: "80px" },
                paddingBottom: isApproved ? "80px" : "32px",
            }}
        >
            {/* Fixed Header Bar */}
            <Box
                sx={{
                    position: "fixed",
                    top: 0,
                    left: 0,
                    right: 0,
                    zIndex: 1200,
                    width: "100%",
                    boxSizing: "border-box",
                    background: "rgba(30, 41, 59, 0.95)",
                    backdropFilter: "blur(16px)",
                    borderBottom: "1px solid rgba(255, 255, 255, 0.1)",
                    pt: { xs: 5, sm: 3 },
                    pb: 2,
                    px: { xs: 2, sm: 4 },
                }}
            >
                <Container maxWidth="lg" disableGutters>
                    <Box
                        sx={{
                            display: "flex",
                            justifyContent: "space-between",
                            alignItems: "center",
                        }}
                    >
                        <Box>
                            <Typography
                                variant="h6"
                                sx={{
                                    fontWeight: 800,
                                    letterSpacing: "-0.5px",
                                    fontSize: "14px",
                                }}
                            >
                                {isApproved
                                    ? `👋 Hello, ${vendorProfile?.business_name || user?.name}`
                                    : "Vendor Portal"}
                            </Typography>
                            <Typography
                                variant="caption"
                                color="text.secondary"
                            >
                                {isApproved
                                    ? "Vendor Dashboard"
                                    : "Account Verification"}
                            </Typography>
                        </Box>

                        <Box
                            sx={{
                                display: "flex",
                                alignItems: "center",
                                gap: 0.5,
                            }}
                        >
                            {isApproved && (
                                <>
                                    <IconButton
                                        color="inherit"
                                        size="small"
                                        sx={{ p: 0.5 }}
                                        onClick={() =>
                                            navigate("/notifications")
                                        }
                                    >
                                        <Badge
                                            badgeContent={
                                                alertsData?.new_assignments || 0
                                            }
                                            color="error"
                                            sx={{
                                                "& .MuiBadge-badge": {
                                                    fontSize: 10,
                                                    height: 16,
                                                    minWidth: 16,
                                                    padding: "0 4px",
                                                },
                                            }}
                                        >
                                            <NotificationsIcon
                                                sx={{ fontSize: 18 }}
                                            />
                                        </Badge>
                                    </IconButton>

                                    <IconButton
                                        color="inherit"
                                        size="small"
                                        sx={{ p: 0.5 }}
                                        onClick={() => navigate("/profile")}
                                    >
                                        <SettingsIcon sx={{ fontSize: 18 }} />
                                    </IconButton>
                                </>
                            )}

                            <IconButton
                                color="error"
                                size="small"
                                onClick={logout}
                                title="Logout"
                                sx={{ p: 0.5 }}
                            >
                                <LogoutIcon sx={{ fontSize: 18 }} />
                            </IconButton>
                        </Box>
                    </Box>
                </Container>
            </Box>

            {/* MAIN CONTENT AREA */}
            <Container maxWidth="lg" sx={{ mt: 3 }}>
                {!isApproved ? (
                    /* STATE 1: Pending / Rejected View */
                    <Container maxWidth="sm" disableGutters>
                        <Paper
                            elevation={16}
                            sx={{
                                padding: { xs: 3, sm: 4 },
                                borderRadius: 4,
                                backdropFilter: "blur(16px)",
                                background: "rgba(30, 41, 59, 0.85)",
                                border: isRejected
                                    ? "1px solid rgba(239, 68, 68, 0.4)"
                                    : "1px solid rgba(255, 255, 255, 0.1)",
                            }}
                        >
                            {isRejected ? (
                                <Box sx={{ textAlign: "center", mb: 3 }}>
                                    <Box
                                        sx={{
                                            width: 64,
                                            height: 64,
                                            borderRadius: "50%",
                                            background:
                                                "rgba(239, 68, 68, 0.15)",
                                            color: "#ef4444",
                                            display: "flex",
                                            alignItems: "center",
                                            justifyContent: "center",
                                            margin: "0 auto 16px auto",
                                            border: "1px solid rgba(239, 68, 68, 0.3)",
                                        }}
                                    >
                                        <CancelIcon sx={{ fontSize: 36 }} />
                                    </Box>

                                    <Typography
                                        variant="h5"
                                        sx={{
                                            fontWeight: 800,
                                            mb: 1,
                                            color: "#fca5a5",
                                        }}
                                    >
                                        Application Rejected
                                    </Typography>
                                    <Typography
                                        variant="body1"
                                        color="text.secondary"
                                        sx={{ mb: 2 }}
                                    >
                                        Hi{" "}
                                        <strong>
                                            {user?.name ||
                                                vendorProfile?.contact_person ||
                                                "Vendor"}
                                        </strong>
                                        , your application request was rejected
                                        by admin.
                                    </Typography>

                                    <Alert
                                        severity="error"
                                        variant="filled"
                                        sx={{
                                            mb: 2,
                                            textAlign: "left",
                                            borderRadius: 3,
                                            bgcolor: "rgba(239, 68, 68, 0.15)",
                                            color: "#fca5a5",
                                            border: "1px solid rgba(239, 68, 68, 0.3)",
                                        }}
                                    >
                                        <AlertTitle sx={{ fontWeight: 700 }}>
                                            Reason for Rejection
                                        </AlertTitle>
                                        {vendorProfile?.rejection_reason ||
                                            "Inaccurate or incomplete business details provided. Please review and update your profile and resubmit for approval."}
                                    </Alert>
                                </Box>
                            ) : (
                                <Box sx={{ textAlign: "center", mb: 3 }}>
                                    <Box
                                        sx={{
                                            width: 64,
                                            height: 64,
                                            borderRadius: "50%",
                                            background:
                                                "rgba(234, 179, 8, 0.15)",
                                            color: "#eab308",
                                            display: "flex",
                                            alignItems: "center",
                                            justifyContent: "center",
                                            margin: "0 auto 16px auto",
                                            border: "1px solid rgba(234, 179, 8, 0.3)",
                                        }}
                                    >
                                        <HourglassIcon sx={{ fontSize: 36 }} />
                                    </Box>

                                    <Typography
                                        variant="h5"
                                        sx={{ fontWeight: 800, mb: 1 }}
                                    >
                                        ⏳ Registration Under Review
                                    </Typography>
                                    <Typography
                                        variant="body1"
                                        color="text.secondary"
                                    >
                                        Hi{" "}
                                        <strong>
                                            {user?.name ||
                                                vendorProfile?.contact_person ||
                                                "Vendor"}
                                        </strong>
                                        , your profile is being verified.
                                    </Typography>
                                    <Typography
                                        variant="body2"
                                        color="text.secondary"
                                        sx={{ opacity: 0.8, mt: 0.5, mb: 2 }}
                                    >
                                        This usually takes 24 - 48 hours.
                                    </Typography>

                                    <Alert
                                        severity="info"
                                        variant="outlined"
                                        sx={{
                                            mb: 1,
                                            textAlign: "left",
                                            borderRadius: 3,
                                            borderColor:
                                                "rgba(99, 102, 241, 0.4)",
                                            color: "#cbd5e1",
                                            bgcolor: "rgba(15, 23, 42, 0.4)",
                                        }}
                                    >
                                        Your registration request is in queue
                                        for admin verification. You can still
                                        update your details below if something
                                        needs correcting.
                                    </Alert>
                                </Box>
                            )}

                            <Divider
                                sx={{
                                    my: 3,
                                    borderColor: "rgba(255, 255, 255, 0.1)",
                                }}
                            />

                            <Box sx={{ mb: 4 }}>
                                <Typography
                                    variant="subtitle2"
                                    sx={{
                                        fontWeight: 700,
                                        textTransform: "uppercase",
                                        letterSpacing: "0.5px",
                                        color: "#94a3b8",
                                        mb: 2,
                                    }}
                                >
                                    Submitted Details:
                                </Typography>

                                <Stack spacing={1.5}>
                                    <Box
                                        sx={{
                                            display: "flex",
                                            justifyContent: "space-between",
                                            bgcolor: "rgba(15, 23, 42, 0.5)",
                                            p: 1.5,
                                            borderRadius: 2,
                                        }}
                                    >
                                        <Typography
                                            variant="body2"
                                            color="text.secondary"
                                        >
                                            Business Name:
                                        </Typography>
                                        <Typography
                                            variant="body2"
                                            sx={{ fontWeight: 600 }}
                                        >
                                            {vendorProfile?.business_name ||
                                                "N/A"}
                                        </Typography>
                                    </Box>

                                    <Box
                                        sx={{
                                            display: "flex",
                                            justifyContent: "space-between",
                                            bgcolor: "rgba(15, 23, 42, 0.5)",
                                            p: 1.5,
                                            borderRadius: 2,
                                        }}
                                    >
                                        <Typography
                                            variant="body2"
                                            color="text.secondary"
                                        >
                                            Industry:
                                        </Typography>
                                        <Typography
                                            variant="body2"
                                            sx={{ fontWeight: 600 }}
                                        >
                                            {industryNames}
                                        </Typography>
                                    </Box>

                                    <Box
                                        sx={{
                                            display: "flex",
                                            justifyContent: "space-between",
                                            bgcolor: "rgba(15, 23, 42, 0.5)",
                                            p: 1.5,
                                            borderRadius: 2,
                                        }}
                                    >
                                        <Typography
                                            variant="body2"
                                            color="text.secondary"
                                        >
                                            Services:
                                        </Typography>
                                        <Typography
                                            variant="body2"
                                            sx={{ fontWeight: 600 }}
                                        >
                                            {serviceNames}
                                        </Typography>
                                    </Box>
                                </Stack>
                            </Box>

                            <Grid container spacing={2} sx={{ mt: 2 }}>
                                <Grid size={{ xs: 12 }}>
                                    <Button
                                        fullWidth
                                        variant={
                                            isRejected
                                                ? "contained"
                                                : "outlined"
                                        }
                                        color={
                                            isRejected ? "primary" : "inherit"
                                        }
                                        startIcon={<EditIcon />}
                                        onClick={() =>
                                            navigate("/profile/edit")
                                        }
                                        sx={{
                                            py: 1.2,
                                            borderRadius: 2,
                                            fontWeight: 700,
                                        }}
                                    >
                                        {isRejected
                                            ? "Edit Profile & Resubmit"
                                            : "Edit Profile"}
                                    </Button>
                                </Grid>
                            </Grid>
                        </Paper>
                    </Container>
                ) : (
                    /* STATE 2: Approved Vendor View — one consistent layout for everyone */
                    <Stack spacing={3}>
                        {renderActionNeeded()}
                        {renderSummaryCards()}
                        {renderCreateOrderButton()}
                        {assignedData.length > 0 && renderAssignedToList()}
                        {renderActivityFeed()}
                    </Stack>
                )}
            </Container>

            {/* Bottom Navigation ONLY rendered after vendor approval */}
            {isApproved && (
                <BottomNav
                    value={navTab}
                    onChange={(_, val) => setNavTab(val)}
                />
            )}
        </Box>
    );
};

export default Dashboard;
