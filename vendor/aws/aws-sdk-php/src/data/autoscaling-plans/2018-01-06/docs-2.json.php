<?php
// This file was auto-generated from sdk-root/src/data/autoscaling-plans/2018-01-06/docs-2.json
return [ 'version' => '2.0', 'service' => '<fullname>AWS Auto Scaling</fullname> <p>Use AWS Auto Scaling to quickly discover all the scalable AWS resources for your application and configure dynamic scaling for your scalable resources.</p> <p>To get started, create a scaling plan with a set of instructions used to configure dynamic scaling for the scalable resources in your application. AWS Auto Scaling creates target tracking scaling policies for the scalable resources in your scaling plan. Target tracking scaling policies adjust the capacity of your scalable resource as required to maintain resource utilization at the target value that you specified.</p>', 'operations' => [ 'CreateScalingPlan' => '<p>Creates a scaling plan.</p> <p>A scaling plan contains a set of instructions used to configure dynamic scaling for the scalable resources in your application. AWS Auto Scaling creates target tracking scaling policies based on the scaling instructions in your scaling plan.</p>', 'DeleteScalingPlan' => '<p>Deletes the specified scaling plan.</p>', 'DescribeScalingPlanResources' => '<p>Describes the scalable resources in the specified scaling plan.</p>', 'DescribeScalingPlans' => '<p>Describes the specified scaling plans or all of your scaling plans.</p>', 'UpdateScalingPlan' => '<p>Updates the scaling plan for the specified scaling plan.</p> <p>You cannot update a scaling plan if it is in the process of being created, updated, or deleted.</p>', ], 'shapes' => [ 'ApplicationSource' => [ 'base' => '<p>Represents an application source.</p>', 'refs' => [ 'ApplicationSources$member' => NULL, 'CreateScalingPlanRequest$ApplicationSource' => '<p>A CloudFormation stack or set of tags. You can create one scaling plan per application source.</p>', 'ScalingPlan$ApplicationSource' => '<p>The application source.</p>', 'UpdateScalingPlanRequest$ApplicationSource' => '<p>A CloudFormation stack or set of tags.</p>', ], ], 'ApplicationSources' => [ 'base' => NULL, 'refs' => [ 'DescribeScalingPlansRequest$ApplicationSources' => '<p>The sources for the applications (up to 10). If you specify scaling plan names, you cannot specify application sources.</p>', ], ], 'ConcurrentUpdateException' => [ 'base' => '<p>Concurrent updates caused an exception, for example, if you request an update to a scaling plan that already has a pending update.</p>', 'refs' => [], ], 'Cooldown' => [ 'base' => NULL, 'refs' => [ 'TargetTrackingConfiguration$ScaleOutCooldown' => '<p>The amount of time, in seconds, after a scale out activity completes before another scale out activity can start. This value is not used if the scalable resource is an Auto Scaling group.</p> <p>While the cooldown period is in effect, the capacity that has been added by the previous scale out event that initiated the cooldown is calculated as part of the desired capacity for the next scale out. The intention is to continuously (but not excessively) scale out.</p>', 'TargetTrackingConfiguration$ScaleInCooldown' => '<p>The amount of time, in seconds, after a scale in activity completes before another scale in activity can start. This value is not used if the scalable resource is an Auto Scaling group.</p> <p>The cooldown period is used to block subsequent scale in requests until it has expired. The intention is to scale in conservatively to protect your application\'s availability. However, if another alarm triggers a scale out policy during the cooldown period after a scale-in, AWS Auto Scaling scales out your scalable target immediately.</p>', 'TargetTrackingConfiguration$EstimatedInstanceWarmup' => '<p>The estimated time, in seconds, until a newly launched instance can contribute to the CloudWatch metrics. This value is used only if the resource is an Auto Scaling group.</p>', ], ], 'CreateScalingPlanRequest' => [ 'base' => NULL, 'refs' => [], ], 'CreateScalingPlanResponse' => [ 'base' => NULL, 'refs' => [], ], 'CustomizedScalingMetricSpecification' => [ 'base' => '<p>Represents a customized metric for a target tracking policy.</p>', 'refs' => [ 'TargetTrackingConfiguration$CustomizedScalingMetricSpecification' => '<p>A customized metric.</p>', ], ], 'DeleteScalingPlanRequest' => [ 'base' => NULL, 'refs' => [], ], 'DeleteScalingPlanResponse' => [ 'base' => NULL, 'refs' => [], ], 'DescribeScalingPlanResourcesRequest' => [ 'base' => NULL, 'refs' => [], ], 'DescribeScalingPlanResourcesResponse' => [ 'base' => NULL, 'refs' => [], ], 'DescribeScalingPlansRequest' => [ 'base' => NULL, 'refs' => [], ], 'DescribeScalingPlansResponse' => [ 'base' => NULL, 'refs' => [], ], 'DisableScaleIn' => [ 'base' => NULL, 'refs' => [ 'TargetTrackingConfiguration$DisableScaleIn' => '<p>Indicates whether scale in by the target tracking policy is disabled. If the value is <code>true</code>, scale in is disabled and the target tracking policy won\'t remove capacity from the scalable resource. Otherwise, scale in is enabled and the target tracking policy can remove capacity from the scalable resource. The default value is <code>false</code>.</p>', ], ], 'ErrorMessage' => [ 'base' => NULL, 'refs' => [ 'ConcurrentUpdateException$Message' => NULL, 'InternalServiceException$Message' => NULL, 'InvalidNextTokenException$Message' => NULL, 'LimitExceededException$Message' => NULL, 'ObjectNotFoundException$Message' => NULL, 'ValidationException$Message' => NULL, ], ], 'InternalServiceException' => [ 'base' => '<p>The service encountered an internal error.</p>', 'refs' => [], ], 'InvalidNextTokenException' => [ 'base' => '<p>The token provided is not valid.</p>', 'refs' => [], ], 'LimitExceededException' => [ 'base' => '<p>Your account exceeded a limit. This exception is thrown when a per-account resource limit is exceeded.</p>', 'refs' => [], ], 'MaxResults' => [ 'base' => NULL, 'refs' => [ 'DescribeScalingPlanResourcesRequest$MaxResults' => '<p>The maximum number of scalable resources to return. This value can be between 1 and 50. The default value is 50.</p>', 'DescribeScalingPlansRequest$MaxResults' => '<p>The maximum number of scalable resources to return. This value can be between 1 and 50. The default value is 50.</p>', ], ], 'MetricDimension' => [ 'base' => '<p>Represents a dimension for a customized metric.</p>', 'refs' => [ 'MetricDimensions$member' => NULL, ], ], 'MetricDimensionName' => [ 'base' => NULL, 'refs' => [ 'MetricDimension$Name' => '<p>The name of the dimension.</p>', ], ], 'MetricDimensionValue' => [ 'base' => NULL, 'refs' => [ 'MetricDimension$Value' => '<p>The value of the dimension.</p>', ], ], 'MetricDimensions' => [ 'base' => NULL, 'refs' => [ 'CustomizedScalingMetricSpecification$Dimensions' => '<p>The dimensions of the metric.</p>', ], ], 'MetricName' => [ 'base' => NULL, 'refs' => [ 'CustomizedScalingMetricSpecification$MetricName' => '<p>The name of the metric.</p>', ], ], 'MetricNamespace' => [ 'base' => NULL, 'refs' => [ 'CustomizedScalingMetricSpecification$Namespace' => '<p>The namespace of the metric.</p>', ], ], 'MetricScale' => [ 'base' => NULL, 'refs' => [ 'TargetTrackingConfiguration$TargetValue' => '<p>The target value for the metric. The range is 8.515920e-109 to 1.174271e+108 (Base 10) or 2e-360 to 2e360 (Base 2).</p>', ], ], 'MetricStatistic' => [ 'base' => NULL, 'refs' => [ 'CustomizedScalingMetricSpecification$Statistic' => '<p>The statistic of the metric.</p>', ], ], 'MetricUnit' => [ 'base' => NULL, 'refs' => [ 'CustomizedScalingMetricSpecification$Unit' => '<p>The unit of the metric.</p>', ], ], 'NextToken' => [ 'base' => NULL, 'refs' => [ 'DescribeScalingPlanResourcesRequest$NextToken' => '<p>The token for the next set of results.</p>', 'DescribeScalingPlanResourcesResponse$NextToken' => '<p>The token required to get the next set of results. This value is <code>null</code> if there are no more results to return.</p>', 'DescribeScalingPlansRequest$NextToken' => '<p>The token for the next set of results.</p>', 'DescribeScalingPlansResponse$NextToken' => '<p>The token required to get the next set of results. This value is <code>null</code> if there are no more results to return.</p>', ], ], 'ObjectNotFoundException' => [ 'base' => '<p>The specified object could not be found.</p>', 'refs' => [], ], 'PolicyName' => [ 'base' => NULL, 'refs' => [ 'ScalingPolicy$PolicyName' => '<p>The name of the scaling policy.</p>', ], ], 'PolicyType' => [ 'base' => NULL, 'refs' => [ 'ScalingPolicy$PolicyType' => '<p>The type of scaling policy.</p>', ], ], 'PredefinedScalingMetricSpecification' => [ 'base' => '<p>Represents a predefined metric for a target tracking policy.</p>', 'refs' => [ 'TargetTrackingConfiguration$PredefinedScalingMetricSpecification' => '<p>A predefined metric.</p>', ], ], 'ResourceCapacity' => [ 'base' => NULL, 'refs' => [ 'ScalingInstruction$MinCapacity' => '<p>The minimum value to scale to in response to a scale in event.</p>', 'ScalingInstruction$MaxCapacity' => '<p>The maximum value to scale to in response to a scale out event.</p>', ], ], 'ResourceIdMaxLen1600' => [ 'base' => NULL, 'refs' => [ 'ScalingInstruction$ResourceId' => '<p>The ID of the resource. This string consists of the resource type and unique identifier.</p> <ul> <li> <p>Auto Scaling group - The resource type is <code>autoScalingGroup</code> and the unique identifier is the name of the Auto Scaling group. Example: <code>autoScalingGroup/my-asg</code>.</p> </li> <li> <p>ECS service - The resource type is <code>service</code> and the unique identifier is the cluster name and service name. Example: <code>service/default/sample-webapp</code>.</p> </li> <li> <p>Spot fleet request - The resource type is <code>spot-fleet-request</code> and the unique identifier is the Spot fleet request ID. Example: <code>spot-fleet-request/sfr-73fbd2ce-aa30-494c-8788-1cee4EXAMPLE</code>.</p> </li> <li> <p>DynamoDB table - The resource type is <code>table</code> and the unique identifier is the resource ID. Example: <code>table/my-table</code>.</p> </li> <li> <p>DynamoDB global secondary index - The resource type is <code>index</code> and the unique identifier is the resource ID. Example: <code>table/my-table/index/my-table-index</code>.</p> </li> <li> <p>Aurora DB cluster - The resource type is <code>cluster</code> and the unique identifier is the cluster name. Example: <code>cluster:my-db-cluster</code>.</p> </li> </ul>', 'ScalingPlanResource$ResourceId' => '<p>The ID of the resource. This string consists of the resource type and unique identifier.</p> <ul> <li> <p>Auto Scaling group - The resource type is <code>autoScalingGroup</code> and the unique identifier is the name of the Auto Scaling group. Example: <code>autoScalingGroup/my-asg</code>.</p> </li> <li> <p>ECS service - The resource type is <code>service</code> and the unique identifier is the cluster name and service name. Example: <code>service/default/sample-webapp</code>.</p> </li> <li> <p>Spot fleet request - The resource type is <code>spot-fleet-request</code> and the unique identifier is the Spot fleet request ID. Example: <code>spot-fleet-request/sfr-73fbd2ce-aa30-494c-8788-1cee4EXAMPLE</code>.</p> </li> <li> <p>DynamoDB table - The resource type is <code>table</code> and the unique identifier is the resource ID. Example: <code>table/my-table</code>.</p> </li> <li> <p>DynamoDB global secondary index - The resource type is <code>index</code> and the unique identifier is the resource ID. Example: <code>table/my-table/index/my-table-index</code>.</p> </li> <li> <p>Aurora DB cluster - The resource type is <code>cluster</code> and the unique identifier is the cluster name. Example: <code>cluster:my-db-cluster</code>.</p> </li> </ul>', ], ], 'ResourceLabel' => [ 'base' => NULL, 'refs' => [ 'PredefinedScalingMetricSpecification$ResourceLabel' => '<p>Identifies the resource associated with the metric type. You can\'t specify a resource label unless the metric type is <code>ALBRequestCountPerTarget</code> and there is a target group for an Application Load Balancer attached to the Auto Scaling group, Spot Fleet request, or ECS service.</p> <p>The format is app/&lt;load-balancer-name&gt;/&lt;load-balancer-id&gt;/targetgroup/&lt;target-group-name&gt;/&lt;target-group-id&gt;, where:</p> <ul> <li> <p>app/&lt;load-balancer-name&gt;/&lt;load-balancer-id&gt; is the final portion of the load balancer ARN</p> </li> <li> <p>targetgroup/&lt;target-group-name&gt;/&lt;target-group-id&gt; is the final portion of the target group ARN.</p> </li> </ul>', ], ], 'ScalableDimension' => [ 'base' => NULL, 'refs' => [ 'ScalingInstruction$ScalableDimension' => '<p>The scalable dimension associated with the resource.</p> <ul> <li> <p> <code>autoscaling:autoScalingGroup:DesiredCapacity</code> - The desired capacity of an Auto Scaling group.</p> </li> <li> <p> <code>ecs:service:DesiredCount</code> - The desired task count of an ECS service.</p> </li> <li> <p> <code>ec2:spot-fleet-request:TargetCapacity</code> - The target capacity of a Spot fleet request.</p> </li> <li> <p> <code>dynamodb:table:ReadCapacityUnits</code> - The provisioned read capacity for a DynamoDB table.</p> </li> <li> <p> <code>dynamodb:table:WriteCapacityUnits</code> - The provisioned write capacity for a DynamoDB table.</p> </li> <li> <p> <code>dynamodb:index:ReadCapacityUnits</code> - The provisioned read capacity for a DynamoDB global secondary index.</p> </li> <li> <p> <code>dynamodb:index:WriteCapacityUnits</code> - The provisioned write capacity for a DynamoDB global secondary index.</p> </li> <li> <p> <code>rds:cluster:ReadReplicaCount</code> - The count of Aurora Replicas in an Aurora DB cluster. Available for Aurora MySQL-compatible edition.</p> </li> </ul>', 'ScalingPlanResource$ScalableDimension' => '<p>The scalable dimension for the resource.</p> <ul> <li> <p> <code>autoscaling:autoScalingGroup:DesiredCapacity</code> - The desired capacity of an Auto Scaling group.</p> </li> <li> <p> <code>ecs:service:DesiredCount</code> - The desired task count of an ECS service.</p> </li> <li> <p> <code>ec2:spot-fleet-request:TargetCapacity</code> - The target capacity of a Spot fleet request.</p> </li> <li> <p> <code>dynamodb:table:ReadCapacityUnits</code> - The provisioned read capacity for a DynamoDB table.</p> </li> <li> <p> <code>dynamodb:table:WriteCapacityUnits</code> - The provisioned write capacity for a DynamoDB table.</p> </li> <li> <p> <code>dynamodb:index:ReadCapacityUnits</code> - The provisioned read capacity for a DynamoDB global secondary index.</p> </li> <li> <p> <code>dynamodb:index:WriteCapacityUnits</code> - The provisioned write capacity for a DynamoDB global secondary index.</p> </li> <li> <p> <code>rds:cluster:ReadReplicaCount</code> - The count of Aurora Replicas in an Aurora DB cluster. Available for Aurora MySQL-compatible edition.</p> </li> </ul>', ], ], 'ScalingInstruction' => [ 'base' => '<p>Specifies the scaling configuration for a scalable resource.</p>', 'refs' => [ 'ScalingInstructions$member' => NULL, ], ], 'ScalingInstructions' => [ 'base' => NULL, 'refs' => [ 'CreateScalingPlanRequest$ScalingInstructions' => '<p>The scaling instructions.</p>', 'ScalingPlan$ScalingInstructions' => '<p>The scaling instructions.</p>', 'UpdateScalingPlanRequest$ScalingInstructions' => '<p>The scaling instructions.</p>', ], ], 'ScalingMetricType' => [ 'base' => NULL, 'refs' => [ 'PredefinedScalingMetricSpecification$PredefinedScalingMetricType' => '<p>The metric type. The <code>ALBRequestCountPerTarget</code> metric type applies only to Auto Scaling groups, Sport Fleet requests, and ECS services.</p>', ], ], 'ScalingPlan' => [ 'base' => '<p>Represents a scaling plan.</p>', 'refs' => [ 'ScalingPlans$member' => NULL, ], ], 'ScalingPlanName' => [ 'base' => NULL, 'refs' => [ 'CreateScalingPlanRequest$ScalingPlanName' => '<p>The name of the scaling plan. Names cannot contain vertical bars, colons, or forward slashes.</p>', 'DeleteScalingPlanRequest$ScalingPlanName' => '<p>The name of the scaling plan.</p>', 'DescribeScalingPlanResourcesRequest$ScalingPlanName' => '<p>The name of the scaling plan.</p>', 'ScalingPlan$ScalingPlanName' => '<p>The name of the scaling plan.</p>', 'ScalingPlanNames$member' => NULL, 'ScalingPlanResource$ScalingPlanName' => '<p>The name of the scaling plan.</p>', 'UpdateScalingPlanRequest$ScalingPlanName' => '<p>The name of the scaling plan.</p>', ], ], 'ScalingPlanNames' => [ 'base' => NULL, 'refs' => [ 'DescribeScalingPlansRequest$ScalingPlanNames' => '<p>The names of the scaling plans (up to 10). If you specify application sources, you cannot specify scaling plan names.</p>', ], ], 'ScalingPlanResource' => [ 'base' => '<p>Represents a scalable resource.</p>', 'refs' => [ 'ScalingPlanResources$member' => NULL, ], ], 'ScalingPlanResources' => [ 'base' => NULL, 'refs' => [ 'DescribeScalingPlanResourcesResponse$ScalingPlanResources' => '<p>Information about the scalable resources.</p>', ], ], 'ScalingPlanStatusCode' => [ 'base' => NULL, 'refs' => [ 'ScalingPlan$StatusCode' => '<p>The status of the scaling plan.</p> <ul> <li> <p> <code>Active</code> - The scaling plan is active.</p> </li> <li> <p> <code>ActiveWithProblems</code> - The scaling plan is active, but the scaling configuration for one or more resources could not be applied.</p> </li> <li> <p> <code>CreationInProgress</code> - The scaling plan is being created.</p> </li> <li> <p> <code>CreationFailed</code> - The scaling plan could not be created.</p> </li> <li> <p> <code>DeletionInProgress</code> - The scaling plan is being deleted.</p> </li> <li> <p> <code>DeletionFailed</code> - The scaling plan could not be deleted.</p> </li> </ul>', ], ], 'ScalingPlanVersion' => [ 'base' => NULL, 'refs' => [ 'CreateScalingPlanResponse$ScalingPlanVersion' => '<p>The version of the scaling plan. This value is always 1.</p>', 'DeleteScalingPlanRequest$ScalingPlanVersion' => '<p>The version of the scaling plan.</p>', 'DescribeScalingPlanResourcesRequest$ScalingPlanVersion' => '<p>The version of the scaling plan.</p>', 'DescribeScalingPlansRequest$ScalingPlanVersion' => '<p>The version of the scaling plan. If you specify a scaling plan version, you must also specify a scaling plan name.</p>', 'ScalingPlan$ScalingPlanVersion' => '<p>The version of the scaling plan.</p>', 'ScalingPlanResource$ScalingPlanVersion' => '<p>The version of the scaling plan.</p>', 'UpdateScalingPlanRequest$ScalingPlanVersion' => '<p>The version number.</p>', ], ], 'ScalingPlans' => [ 'base' => NULL, 'refs' => [ 'DescribeScalingPlansResponse$ScalingPlans' => '<p>Information about the scaling plans.</p>', ], ], 'ScalingPolicies' => [ 'base' => NULL, 'refs' => [ 'ScalingPlanResource$ScalingPolicies' => '<p>The scaling policies.</p>', ], ], 'ScalingPolicy' => [ 'base' => '<p>Represents a scaling policy.</p>', 'refs' => [ 'ScalingPolicies$member' => NULL, ], ], 'ScalingStatusCode' => [ 'base' => NULL, 'refs' => [ 'ScalingPlanResource$ScalingStatusCode' => '<p>The scaling status of the resource.</p> <ul> <li> <p> <code>Active</code> - The scaling configuration is active.</p> </li> <li> <p> <code>Inactive</code> - The scaling configuration is not active because the scaling plan is being created or the scaling configuration could not be applied. Check the status message for more information.</p> </li> <li> <p> <code>PartiallyActive</code> - The scaling configuration is partially active because the scaling plan is being created or deleted or the scaling configuration could not be fully applied. Check the status message for more information.</p> </li> </ul>', ], ], 'ServiceNamespace' => [ 'base' => NULL, 'refs' => [ 'ScalingInstruction$ServiceNamespace' => '<p>The namespace of the AWS service.</p>', 'ScalingPlanResource$ServiceNamespace' => '<p>The namespace of the AWS service.</p>', ], ], 'TagFilter' => [ 'base' => '<p>Represents a tag.</p>', 'refs' => [ 'TagFilters$member' => NULL, ], ], 'TagFilters' => [ 'base' => NULL, 'refs' => [ 'ApplicationSource$TagFilters' => '<p>A set of tags (up to 50).</p>', ], ], 'TagValues' => [ 'base' => NULL, 'refs' => [ 'TagFilter$Values' => '<p>The tag values (0 to 20).</p>', ], ], 'TargetTrackingConfiguration' => [ 'base' => '<p>Represents a target tracking scaling policy.</p>', 'refs' => [ 'ScalingPolicy$TargetTrackingConfiguration' => '<p>The target tracking scaling policy.</p>', 'TargetTrackingConfigurations$member' => NULL, ], ], 'TargetTrackingConfigurations' => [ 'base' => NULL, 'refs' => [ 'ScalingInstruction$TargetTrackingConfigurations' => '<p>The target tracking scaling policies (up to 10).</p>', ], ], 'TimestampType' => [ 'base' => NULL, 'refs' => [ 'ScalingPlan$StatusStartTime' => '<p>The Unix timestamp when the scaling plan entered the current status.</p>', 'ScalingPlan$CreationTime' => '<p>The Unix timestamp when the scaling plan was created.</p>', ], ], 'UpdateScalingPlanRequest' => [ 'base' => NULL, 'refs' => [], ], 'UpdateScalingPlanResponse' => [ 'base' => NULL, 'refs' => [], ], 'ValidationException' => [ 'base' => '<p>An exception was thrown for a validation issue. Review the parameters provided.</p>', 'refs' => [], ], 'XmlString' => [ 'base' => NULL, 'refs' => [ 'ApplicationSource$CloudFormationStackARN' => '<p>The Amazon Resource Name (ARN) of a CloudFormation stack.</p>', 'ScalingPlan$StatusMessage' => '<p>A simple message about the current status of the scaling plan.</p>', 'ScalingPlanResource$ScalingStatusMessage' => '<p>A simple message about the current scaling status of the resource.</p>', ], ], 'XmlStringMaxLen128' => [ 'base' => NULL, 'refs' => [ 'TagFilter$Key' => '<p>The tag key.</p>', ], ], 'XmlStringMaxLen256' => [ 'base' => NULL, 'refs' => [ 'TagValues$member' => NULL, ], ], ],];
