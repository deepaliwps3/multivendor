import React, { useEffect, useState } from "react";
import { useNavigate } from "react-router-dom";
import {
    Add as AddIcon,
    Assignment as AssignmentIcon,
    CheckCircle as CheckCircleIcon,
    Edit as EditIcon,
    ExitToApp as LogoutIcon,
    HourglassEmpty as HourglassIcon,
    Notifications as NotificationsIcon,
    Payment as PaymentIcon,
    Settings as SettingsIcon,
    SupportAgent as SupportIcon,
    Warning as WarningIcon,
} from "@mui/icons-material";
import {
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

    // Bottom Navigation tab state
    const [navTab, setNavTab] = useState(0);

    useEffect(() => {
        const fetchDashboardData = async () => {
            setLoading(true);
            try {
                const profile = await vendorApi.getMe();
                setVendorProfile(profile);

                if (profile.approval_status === "approved") {
                    const [alerts, summary, activity] = await Promise.all([
                        vendorApi.getAlerts(),
                        vendorApi.getSummary(),
                        vendorApi.getActivity(),
                    ]);
                    setAlertsData(alerts);
                    setSummaryData(summary);
                    setActivityData(activity);
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
    const vendorType = vendorProfile?.vendor_type || "both";

    /* =========================================================================
       SUB-COMPONENTS FOR DYNAMIC SECTION ORDERING
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
                    )}
                    {alertsData.awaiting_my_assignment > 0 && (
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
                    )}
                    {alertsData.overdue_stages > 0 && (
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
                            </strong>{" "}
                            ({alertsData.overdue_details})
                        </Typography>
                    )}
                </Stack>
            </Paper>
        ) : null;

    // Section 3: Summary Cards (4-Grid)
    const renderSummaryCards = () => (
        <Grid container spacing={2}>
            <Grid size={{ xs: 6, sm: 3 }}>
                <Card
                    sx={{
                        bgcolor: "rgba(30, 41, 59, 0.8)",
                        border: "1px solid rgba(255, 255, 255, 0.1)",
                        borderRadius: 3,
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
                            {summaryData?.active_orders ?? 12}
                        </Typography>
                    </CardContent>
                </Card>
            </Grid>

            <Grid size={{ xs: 6, sm: 3 }}>
                <Card
                    sx={{
                        bgcolor: "rgba(30, 41, 59, 0.8)",
                        border: "1px solid rgba(255, 255, 255, 0.1)",
                        borderRadius: 3,
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
                            {summaryData?.assigned_to_me ?? 5}
                        </Typography>
                    </CardContent>
                </Card>
            </Grid>

            <Grid size={{ xs: 6, sm: 3 }}>
                <Card
                    sx={{
                        bgcolor: "rgba(30, 41, 59, 0.8)",
                        border: "1px solid rgba(255, 255, 255, 0.1)",
                        borderRadius: 3,
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
                            {summaryData?.awaiting_my_assignment ?? 2}
                        </Typography>
                    </CardContent>
                </Card>
            </Grid>

            <Grid size={{ xs: 6, sm: 3 }}>
                <Card
                    sx={{
                        bgcolor: "rgba(30, 41, 59, 0.8)",
                        border: "1px solid rgba(255, 255, 255, 0.1)",
                        borderRadius: 3,
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
                            {summaryData?.monthly_earnings ?? "₹42,500"}
                        </Typography>
                    </CardContent>
                </Card>
            </Grid>
        </Grid>
    );

    // Section 4: Create Order Button (Primary or Secondary)
    const renderCreateOrderButton = (isSecondary = false) => (
        <Button
            fullWidth
            variant={isSecondary ? "outlined" : "contained"}
            size="large"
            startIcon={<AddIcon />}
            sx={{
                py: isSecondary ? 1.4 : 1.8,
                fontSize: isSecondary ? "0.95rem" : "1.05rem",
                fontWeight: 700,
                borderRadius: 3,
                ...(isSecondary
                    ? { borderColor: "#6366f1", color: "#818cf8" }
                    : {
                          background:
                              "linear-gradient(135deg, #6366f1 0%, #4f46e5 100%)",
                          boxShadow: "0 8px 20px rgba(79, 70, 229, 0.4)",
                          "&:hover": {
                              background:
                                  "linear-gradient(135deg, #4f46e5 0%, #4338ca 100%)",
                          },
                      }),
            }}
        >
            + Create New Order
        </Button>
    );

    // Assigned to Me direct list for executor vendor_type
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
            <Typography
                variant="h6"
                sx={{ fontWeight: 700, mb: 1, color: "#38bdf8" }}
            >
                📋 Assigned to Me List
            </Typography>
            <Typography variant="body2" color="text.secondary" sx={{ mb: 2 }}>
                Active stage work assigned to your workshop:
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
                    <Typography variant="body2" sx={{ fontWeight: 600 }}>
                        Order #1234 — Polishing
                    </Typography>
                    <Chip label="In Progress" color="warning" size="small" />
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
                    <Typography variant="body2" sx={{ fontWeight: 600 }}>
                        Order #1245 — Metal Casting
                    </Typography>
                    <Chip label="Assigned" color="info" size="small" />
                </Box>
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
                <Button size="small" sx={{ textTransform: "none" }}>
                    View All Activity
                </Button>
            </Box>

            <Divider sx={{ mb: 2, borderColor: "rgba(255, 255, 255, 0.1)" }} />

            <Stack spacing={2}>
                {activityData.map((item) => (
                    <Box
                        key={item.id}
                        sx={{
                            display: "flex",
                            alignItems: "center",
                            justifyContent: "space-between",
                            bgcolor: "rgba(15, 23, 42, 0.5)",
                            p: 2,
                            borderRadius: 2,
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
                                <CheckCircleIcon sx={{ color: "#10b981" }} />
                            )}
                            {item.type === "payment_received" && (
                                <PaymentIcon sx={{ color: "#f59e0b" }} />
                            )}
                            {item.type === "new_order_assigned" && (
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
        </Paper>
    );

    const industryNames =
        vendorProfile?.industries.map((i) => i.name).join(", ") || "Jewellery";
    const serviceNames =
        vendorProfile?.services.map((s) => s.name).join(", ") ||
        "Making, Polishing";

    return (
        <Box
            sx={{
                minHeight: "100vh",
                background:
                    "linear-gradient(135deg, #0f172a 0%, #1e293b 50%, #0f172a 100%)",
                color: "#ffffff",
                pt: { xs: "96px", sm: "80px" }, // Top padding to offset the fixed top header cleanly
                paddingBottom: isApproved ? "80px" : "32px",
            }}
        >
            {/* 100% Fixed Header Bar (Never moves during scroll) */}
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
                    pt: { xs: 5, sm: 3 }, // Safe-area top padding for mobile status bar
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
                                }}
                            >
                                {isApproved
                                    ? `👋 Welcome back, ${vendorProfile?.business_name || user?.name}`
                                    : "Vendor Portal"}
                            </Typography>
                            <Typography
                                variant="caption"
                                color="text.secondary"
                            >
                                {isApproved
                                    ? `Vendor Dashboard • Role: ${vendorType.toUpperCase()}`
                                    : "Account Verification"}
                            </Typography>
                        </Box>

                        <Box
                            sx={{
                                display: "flex",
                                alignItems: "center",
                                gap: 1,
                            }}
                        >
                            {isApproved && (
                                <>
                                    <IconButton color="inherit" size="medium">
                                        <Badge badgeContent={3} color="error">
                                            <NotificationsIcon />
                                        </Badge>
                                    </IconButton>

                                    <IconButton color="inherit" size="medium">
                                        <SettingsIcon />
                                    </IconButton>
                                </>
                            )}

                            <Button
                                variant="outlined"
                                color="error"
                                size="small"
                                startIcon={<LogoutIcon />}
                                onClick={logout}
                                sx={{ ml: 1, borderRadius: 2 }}
                            ></Button>
                        </Box>
                    </Box>
                </Container>
            </Box>

            {/* MAIN CONTENT AREA */}
            <Container maxWidth="lg" sx={{ mt: 3 }}>
                {!isApproved ? (
                    /* STATE 1: Pending Approval View */
                    <Container maxWidth="sm" disableGutters>
                        <Paper
                            elevation={16}
                            sx={{
                                padding: { xs: 3, sm: 4 },
                                borderRadius: 4,
                                backdropFilter: "blur(16px)",
                                background: "rgba(30, 41, 59, 0.85)",
                                border: "1px solid rgba(255, 255, 255, 0.1)",
                            }}
                        >
                            <Box sx={{ textAlign: "center", mb: 3 }}>
                                <Box
                                    sx={{
                                        width: 64,
                                        height: 64,
                                        borderRadius: "50%",
                                        background: "rgba(234, 179, 8, 0.15)",
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
                                    sx={{ opacity: 0.8, mt: 0.5 }}
                                >
                                    This usually takes 24 - 48 hours.
                                </Typography>
                            </Box>

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
                                                "Ramesh Karigar Works"}
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

                            <Grid container spacing={1}>
                                <Grid size={{ xs: 6 }}>
                                    <Button
                                        fullWidth
                                        variant="outlined"
                                        startIcon={<EditIcon />}
                                        onClick={() => navigate('/profile/edit')}
                                        sx={{ py: 1.2, borderRadius: 2 }}
                                    >
                                        Profile
                                    </Button>
                                </Grid>
                                <Grid size={{ xs: 6 }}>
                                    <Button
                                        fullWidth
                                        variant="contained"
                                        startIcon={<SupportIcon />}
                                        sx={{
                                            py: 1.2,
                                            borderRadius: 2,
                                            fontWeight: 600,
                                        }}
                                    >
                                        Support
                                    </Button>
                                </Grid>
                            </Grid>
                        </Paper>
                    </Container>
                ) : (
                    /* STATE 2: Approved Vendor View */
                    <Stack spacing={3}>
                        {vendorType === "originator" && (
                            <>
                                {renderActionNeeded()}
                                {renderCreateOrderButton(false)}
                                {renderSummaryCards()}
                                {renderActivityFeed()}
                            </>
                        )}

                        {vendorType === "executor" && (
                            <>
                                {renderActionNeeded()}
                                {renderAssignedToList()}
                                {renderSummaryCards()}
                                {renderCreateOrderButton(true)}
                                {renderActivityFeed()}
                            </>
                        )}

                        {(vendorType === "both" ||
                            !["originator", "executor"].includes(
                                vendorType,
                            )) && (
                            <>
                                {renderActionNeeded()}
                                {renderSummaryCards()}
                                {renderCreateOrderButton(false)}
                                {renderActivityFeed()}
                            </>
                        )}
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
