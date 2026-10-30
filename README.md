# Diagnosing and Resolving Intermittent API Timeout Failures

## Overview

A production web application began experiencing intermittent API timeout errors that affected approximately 5-10% of customer requests. The failures were difficult to reproduce and appeared to occur only during periods of elevated traffic.

## Initial Symptoms

Users reported sporadic loading failures when accessing account information. Application logs showed an increase in request durations, with some requests exceeding the configured 30-second timeout threshold.

Key observations included:

- Database health metrics appeared normal.
- Infrastructure monitoring showed no CPU or memory saturation.
- Errors were concentrated around a subset of API endpoints.
- Restarting application instances provided temporary relief.

## Investigation Process

I began by correlating application logs, database metrics, and load balancer telemetry to identify common patterns among failed requests.

The analysis revealed that:

1. Requests experiencing timeouts were consistently waiting on outbound service calls.
2. The affected service used a shared HTTP connection pool.
3. During traffic spikes, connection pool utilization reached its configured limit.
4. Requests would queue for available connections, increasing overall latency.

To validate the hypothesis, I enabled additional telemetry around connection acquisition times and reproduced load patterns in a staging environment.

## Root Cause

The root cause was an undersized HTTP connection pool combined with insufficient timeout and retry configurations.

As traffic increased, requests accumulated while waiting for available outbound connections. This caused cascading latency and eventual request timeouts at the application layer.

## Resolution

The implemented solution included:

- Increasing the outbound connection pool size.
- Applying connection-specific timeout settings.
- Introducing circuit-breaker behavior for downstream failures.
- Adding dashboards and alerts for connection pool saturation.
- Optimizing several high-volume API calls to reduce unnecessary requests.

## Results

After deployment:

- Application timeout errors were eliminated under normal operating conditions.
- P95 response times decreased significantly.
- Service stability during peak traffic periods improved.
- New monitoring provided early warning indicators before customer impact.

## Lessons Learned

This incident reinforced the importance of monitoring resource pools, not just server-level metrics. The system appeared healthy from an infrastructure perspective, but application-level dependencies revealed the actual bottleneck. Additional telemetry around resource contention is now included as part of standard observability practices.
